import requests
from bs4 import BeautifulSoup
import json
import time
from urllib.parse import urljoin
import tomllib
from pathlib import Path
from typing import List, Dict, Optional


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
    
    """
    Pobiera: Wszystkie kategorie ze strukturą hierarchiczną
    Zwraca: Lista kategorii z hierarchią (dzieci w 'children')
    """
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
                # Zchodzimy o poziom niżej
                node['children'] = self._build_tree(li, level + 1)
            
            nodes.append(node)
        
        return nodes

    """
    Pobiera: wszystkie produkty ze wszystkich liści kategorii
    Argumenty: categories - drzewo kategorii
    Zwraca: Lista produktów: [{id, name, link, image, price, description, producer, category_name}, ...]
    """
    def scrape_products(self, categories: List[Dict]) -> List[Dict]:
        print("\nETAP 2: Pobieranie produktów...")
        
        products = []
        self._traverse_leaf_categories(categories, products)
        
        print(f"OK: Pobrano {len(products)} produktów")
        return products

    """Przechodzi rekurencyjnie przez drzewo i pobiera produkty tylko z liści"""
    def _traverse_leaf_categories(self, tree: List[Dict], products: List[Dict]):
        for node in tree:
            if len(products) >= self.config.max_products_total:
                return
            
            if node.get('children'):
                # Ma dzieci - schodzimy głębiej
                self._traverse_leaf_categories(node['children'], products)
            else:
                # To liść - pobieramy produkty z tej kategorii
                self._scrape_products_from_category(node, products)

    """Pobiera produkty z jednej kategorii liścia"""
    def _scrape_products_from_category(self, category: Dict, products: List[Dict]):
        category_url = category['url']
        category_name = category['name']
        
        print(f"INFO: Pobieranie produktów z: {category_name}")
        
        page = self.http.get_page(category_url)
        if not page:
            return
        
        # Znajdujemy kontener z produktami
        products_container = page.select_one(self.config.data['page_locators']['products_container'])
        if not products_container:
            print(f"WARNING: Brak kontenera produktów w {category_name}")
            return
        
        # Iterujemy po wszystkich produktach
        product_items = products_container.select(self.config.data['page_locators']['product_item'])
        
        count = 0
        for product_item in product_items:
            if len(products) >= self.config.max_products_total:
                break
            
            # Wyciągamy dane produktu
            product_data = self._extract_product_data(product_item, category_name)
            if product_data:
                products.append(product_data)
                count += 1
        
        print(f"OK: Pobrano {count} produktów z {category_name}")

    """Wyciąga dane z jednego produktu"""
    def _extract_product_data(self, product_item, category_name: str) -> Optional[Dict]:
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
                'category_name': category_name
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
        print("\nETAP 3: Pobieranie szczegółów produktów...")
        
        detailed_products = []
        
        for idx, product in enumerate(products):
            print(f"INFO: [{idx+1}/{len(products)}] {product['name']}")
            
            details = self._fetch_product_details(product['link'])
            if details:
                # Łączymy dane podstawowe ze szczegółami
                full_product = {
                    **product,
                    **details
                }
                detailed_products.append(full_product)
        
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
        photo_links = page.select(locators['high_res_photos'])
        
        # Bierzemy ilość zdjęć zgodnie z configiem
        for link in photo_links[:self.config.max_images_per_product]:
            href = link.get('href')
            if href:
                full_url = urljoin(self.config.base_url, href)
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
    
    # Pobieramy kategorie
    categories = scraper.scrape_categories()
    
    # Pobieramy produkty
    products = scraper.scrape_products(categories)
    
    # Pobieramy szczegóły produktów
    products_detailed = scraper.scrape_product_details(products)
    
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