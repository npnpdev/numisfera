import requests
import json
import time
import tomllib
import re
from bs4 import BeautifulSoup
from pathlib import Path
from urllib.parse import urljoin
from typing import List, Dict, Optional
from concurrent.futures import ThreadPoolExecutor, as_completed
from threading import Lock

class Config:
    def __init__(self, config_path: str = 'config.toml'):
        script_dir = Path(__file__).parent.resolve()
        with open(script_dir / config_path, 'rb') as f:
            data = tomllib.load(f)
        
        self.data = data  # Przechowujemy całe data
        
        self.base_url = data['scraper']['base_url']
        self.user_agent = data['scraper']['user_agent']
        self.request_delay = data['scraper']['request_delay']
        self.timeout = data['scraper']['timeout']
        self.encoding = data['scraper']['encoding']
        self.max_products_total = data['scraper']['max_products_total']
        self.max_images_per_product = data['scraper']['max_images_per_product']
        
        self.sel_categories = data['selectors']['categories']
        
        self.results_dir = script_dir / data['output']['results_dir']
        self.categories_file = data['output']['categories_file']
        self.products_file = data['output']['products_file']
        self.products_detailed_file = data['output']['products_detailed_file']
        self.max_workers = data['scraper'].get('MAX_WORKERS', 10)

class HTTPClient:
    def __init__(self, config: Config):
        self.config = config
        self.session = requests.Session()
        self.session.headers.update({'User-Agent': config.user_agent})
    
    def get_page(self, url: str) -> Optional[BeautifulSoup]:
        time.sleep(self.config.request_delay)
        try:
            response = self.session.get(url, timeout=self.config.timeout)
            response.raise_for_status()
            response.encoding = self.config.encoding
            return BeautifulSoup(response.text, 'lxml')
        except Exception as e:
            print(f"ERROR: {url}: {e}")
            return None

class Scraper:
    def __init__(self, config: Config, http: HTTPClient):
        self.config = config
        self.http = http
    
    """Czyści opis produktu z tagów <iframe> i ich zawartości"""
    def _clean_description(self, description: str) -> str:
        if not description:
            return ''
        # Usuwamy wszystkie iframe wraz z ich zawartością
        description = re.sub(r'<iframe.*?</iframe>', '', description, flags=re.DOTALL | re.IGNORECASE)
        return description

    """Tworzy listę kategorii-liści, każda z pełną ścieżką do niej."""
    def _get_leaf_categories(self, tree: List[Dict]) -> List[Dict]:
        leaf_nodes_with_paths = []
        
        def traverse(nodes: List[Dict], current_path: List[str]):
            for node in nodes:
                # Tworzymy nową ścieżkę dla tej gałęzi, dodając aktualną nazwę
                new_path = current_path + [node['name']]
                
                if 'children' in node and node['children']:
                    # Jeśli są dzieci, idziemy głębiej
                    traverse(node['children'], new_path)
                else:
                    # To jest liść. Zapisujemy jego dane razem z pełną ścieżką.
                    leaf_info = {
                        'name': node['name'],
                        'url': node['url'],
                        'path': new_path
                    }
                    leaf_nodes_with_paths.append(leaf_info)
        
        traverse(tree, [])
        return leaf_nodes_with_paths

    """ Pobiera cała listę kategorii i buduje z niej drzewo kategorii """
    def scrape_categories(self) -> List[Dict]:
        print("ETAP 1: Pobieranie kategorii...")
        
        page = self.http.get_page(self.config.base_url)
        if not page:
            return []
        
        # Znajdujemy root element
        root_li = page.select_one(self.config.sel_categories['menu_root'])
        if not root_li:
            print("ERROR: Nie znaleziono root elementu")
            return []
        
        # Zaczynamy budować drzewo od poziomu 1
        tree = self._build_tree(root_li, level=1)
        
        print(f"OK: Pobrano kategorie")
        return tree
    
    """Buduje drzewo kategorii rekurencyjnie"""
    def _build_tree(self, parent_element, level: int) -> List[Dict]:
        nodes = []
        
        # Szukamy ul.level{level} w bieżącym elemencie
        ul_selector = self.config.sel_categories['ul_template'].format(level=level)
        ul_element = parent_element.select_one(ul_selector)
        
        if not ul_element:
            return nodes
        
        # Iterujemy po wszystkich <li> w tej liście
        list_items = ul_element.select(':scope > li')
        
        for li in list_items:
            # Wyciągamy link i nazwę kategorii
            link_element = li.select_one(self.config.sel_categories['category_link'])
            if not link_element or not link_element.get('href'):
                continue
            
            name = link_element.get_text(strip=True)
            if not name:
                continue
            
            node = {
                'name': name,
                'url': urljoin(self.config.base_url, link_element['href']),
                'children': []
            }
            
            # Sprawdzamy czy li ma klasę "parent" (czyli ma podkategorie)
            if 'parent' in li.get('class', []):
                # Schodzimy o poziom niżej
                node['children'] = self._build_tree(li, level + 1)
            
            nodes.append(node)
        
        return nodes

    """
    Pobiera: wszystkie produkty ze wszystkich liści kategorii
    Zwraca: Lista produktów: [{id, name, link, image, price, description, producer, category_name}, ...]
    """
    def scrape_products(self, categories: List[Dict]) -> List[Dict]:
        print("\nETAP 2: Pobieranie produktów...")
        
        leaf_categories = self._get_leaf_categories(categories)

        all_products = []
        products_lock = Lock()

        with ThreadPoolExecutor(max_workers=self.config.max_workers) as executor:
            futures = {executor.submit(self._scrape_products_from_category, cat): cat for cat in leaf_categories}
            
            print(f"INFO: Uruchamiam {self.config.max_workers} wątków do pobrania produktów z {len(leaf_categories)} kategorii...")

            for future in as_completed(futures):
                category_data = futures[future]
                try:
                    products_from_category = future.result()
                    
                    with products_lock:
                        if len(all_products) < self.config.max_products_total:
                            # Obliczamy ile jeszcze możemy dodać produktów
                            remaining_space = self.config.max_products_total - len(all_products)
                            
                            # Dodajemy tylko tyle, ile brakuje do limitu
                            products_to_add = products_from_category[:remaining_space]
                            all_products.extend(products_to_add)
                            
                            print(f"OK: Pobrano {len(products_from_category)} prod. z '{category_data['name']}'. Dodano {len(products_to_add)}. Łącznie: {len(all_products)}")
                            
                            if len(all_products) >= self.config.max_products_total:
                                print("INFO: Osiągnięto globalny limit produktów. Zatrzymuję pobieranie z kolejnych kategorii.")
                                # Anulujemy pozostałe zadania, które jeszcze się nie rozpoczęły
                                for f in futures:
                                    if not f.done():
                                        f.cancel()
                                break # Przerywamy główną pętlę as_completed
                except Exception as exc:
                    print(f"ERROR: Błąd podczas przetwarzania kategorii {category_data['name']}: {exc}")

        final_products = all_products
        
        print(f"\nOK: Zakończono Etap 2. Pobrano {len(final_products)} produktów.")
        return final_products

    """Pobiera produkty z jednej kategorii liścia"""
    def _scrape_products_from_category(self, category: Dict) -> List[Dict]:
        category_url = category['url']
        category_path = category['path']  # Używamy ścieżki kategorii
        products_from_category = []
        
        page = self.http.get_page(category_url)
        if not page:
            return products_from_category

        products_container = page.select_one(self.config.data['page_locators']['products_container'])
        if not products_container:
            return products_from_category

        product_items = products_container.select(self.config.data['page_locators']['product_item'])
        
        for product_item in product_items:
            # Przekazujemy całą ścieżkę
            product_data = self._extract_product_data(product_item, category_path)
            if product_data:
                products_from_category.append(product_data)
                
        return products_from_category

    """Wyciąga dane z jednego produktu"""
    def _extract_product_data(self, product_item, category_path: List[str]) -> Optional[Dict]:
        try:
            locators = self.config.data['product_locators']
            
            # ID
            product_id = product_item.get(locators['id_attribute'])
            
            # Nazwa
            name_elem = product_item.select_one(locators['name'])
            name = name_elem.get_text(strip=True) if name_elem else None
            
            # Link
            link_elem = product_item.select_one(locators['link'])
            link = urljoin(self.config.base_url, link_elem['href']) if link_elem and link_elem.get('href') else None
            
            if not name or not link:
                return None
            
            return {
                'id': product_id,
                'name': name,
                'link': link,
                'category_path': category_path  # ZAPISUJEMY PEŁNĄ ŚCIEŻKĘ
            }
        
        except Exception as e:
            print(f"ERROR: Wyciąganie danych produktu: {e}")
            return None
        
    """Czyści tekst ceny i zwraca float"""
    def _parse_price(self, price_text: str) -> Optional[float]:
        try:
            clean = ''.join(ch for ch in price_text if ch.isdigit() or ch in ',.')
            clean = clean.replace(',', '.')
            return float(clean) if clean else None
        except Exception:
            return None

    """
    Pobiera: szczegółowe dane dla każdego produktu
    Argumenty: products - Lista produktów
    Zwraca: Lista produktów ze szczegółami: [{name, price, description, attributes, images}]
    """
    def scrape_product_details(self, products: List[Dict]) -> List[Dict]:
        print(f"\nETAP 3: Pobieranie szczegółów dla {len(products)} produktów...")
        
        detailed_products = []

        product_links = [p['link'] for p in products]

        with ThreadPoolExecutor(max_workers=self.config.max_workers) as executor:
            print(f"INFO: Uruchamiam {self.config.max_workers} wątków...")
            results = list(executor.map(self._fetch_product_details, product_links))

        # Połącz wyniki z oryginalnymi danymi
        for i, product in enumerate(products):
            details = results[i]
            if details:
                full_product = {**product, **details}
                detailed_products.append(full_product)
            else:
                print(f"WARNING: Nie udało się pobrać szczegółów dla: {product['name']}")
        
        print(f"OK: Pobrano szczegóły dla {len(detailed_products)} produktów")
        return detailed_products

    """Pobiera szczegółowe dane produktu ze strony produktu"""
    def _fetch_product_details(self, product_url: str) -> Optional[Dict]:
        page = self.http.get_page(product_url)
        if not page:
            return None
        
        try:
            locators = self.config.data['product_details_locators']
            
            # Cena
            price_elem = page.select_one(locators['price'])
            price_text = price_elem.get_text(strip=True) if price_elem else None
            price = self._parse_price(price_text) if price_text else None
            
            # Opis
            desc_elem = page.select_one(locators['description'])
            description = desc_elem.decode_contents() if desc_elem else None
            description = self._clean_description(description)  # Usuwamy <iframe>

            # Zdjęcia
            images = self._extract_high_res_photos(page, locators)
            
            # Atrybuty
            attributes = self._extract_attributes(page, locators)
            
            return {
                'price': price,
                'price_text': price_text,
                'description': description,
                'images': images,
                'attributes': attributes
            }
        
        except Exception as e:
            print(f"ERROR: Pobieranie szczegółów produktu: {e}")
            return None
    
    """Wyciąga linki do zdjęć w wysokiej rozdzielczości"""
    def _extract_high_res_photos(self, page: BeautifulSoup, locators: Dict) -> List[str]:
        images = []

        # Sprawdzamy czy istnieje galeria zdjęć
        photo_links = page.select(locators['high_res_photos'])
        
        if photo_links:
            for link in photo_links[:self.config.max_images_per_product]:
                href = link.get('href')
                if href:
                    full_url = urljoin(self.config.base_url, href)
                    images.append(full_url)
        
        # Jeśli nie ma galerii, sprawdzamy czy jest pojedyncze zdjęcie główne
        elif 'main_single_photo' in locators:
            single_photo_link = page.select_one(locators['main_single_photo'])
            if single_photo_link:
                href = single_photo_link.get('href')
                if href:
                    full_url = urljoin(self.config.base_url, href)
                    # Duplikujemy link tyle razy, ile jest dozwolonych zdjęć
                    for _ in range(self.config.max_images_per_product):
                        images.append(full_url)
        
        return images

    """Wyciąga atrybuty/dane techniczne z tabeli"""
    def _extract_attributes(self, page: BeautifulSoup, locators: Dict) -> Dict[str, str]:
        attributes = {}
        rows = page.select(locators['attributes_table_rows'])
        
        for row in rows:
            key_elem = row.select_one(locators['attribute_key'])
            value_elem = row.select_one(locators['attribute_value'])
            
            if key_elem and value_elem:
                key = key_elem.get_text(strip=True)
                value = value_elem.get_text(strip=True)
                
                if key and value:
                    attributes[key] = value
        
        return attributes

if __name__ == "__main__":
    config = Config()
    http = HTTPClient(config)
    scraper = Scraper(config, http)
    
    start = time.time()
    # Pobieramy kategorie
    categories = scraper.scrape_categories()

    fetch_time = time.time()
    print(f"ETAP 1 zakończony w {fetch_time - start:.1f} sekund")

    # Pobieramy produkty
    products = scraper.scrape_products(categories)
    fetch_time = time.time()
    print(f"ETAP 2 zakończony w {fetch_time - start:.1f} sekund")
    
    # Pobieramy szczegóły produktów
    products_detailed = scraper.scrape_product_details(products)
    fetch_time = time.time()
    print(f"ETAP 3 zakończony w {fetch_time - start:.1f} sekund")
    
    # Zapisujemy wyniki do plików JSON
    config.results_dir.mkdir(exist_ok=True)
    with open(config.results_dir / config.categories_file, 'w', encoding=config.encoding) as f:
        json.dump(categories, f, ensure_ascii=False, indent=2)
    
    with open(config.results_dir / config.products_file, 'w', encoding=config.encoding) as f:
        json.dump(products, f, ensure_ascii=False, indent=2)
    
    with open(config.results_dir / config.products_detailed_file, 'w', encoding=config.encoding) as f:
        json.dump(products_detailed, f, ensure_ascii=False, indent=2)
    
    print(f"Zapisano do: {config.categories_file}")
    print(f"Zapisano do: {config.products_file}")
    print(f"Zapisano do: {config.products_detailed_file}")