import json
import tomllib
from pathlib import Path
from typing import List, Dict
import xml.etree.ElementTree as ET
from prestashop_base import XMLBuilder, APIClient, API_SUCCESS_CODES, CONFIG_FILE
from concurrent.futures import ThreadPoolExecutor
from import_images import process_product_images

"""Importer produktów do PrestaShopa"""
class ProductImporter:
    def __init__(self, config_file: str = CONFIG_FILE):
        self.config = self._load_config(config_file)
        self.api_client = APIClient(
            self.config['prestashop']['api_url'],
            self.config['prestashop']['api_key'],
            self.config['prestashop'].get('verify_ssl', True)
        )
        self.results_dir = Path(__file__).parent.parent / self.config['paths']['results_dir']
        self.category_map = {}
        self.max_workers = self.config['import'].get('MAX_WORKERS', 5)
        self.vat_rate = self.config['import'].get('vat_rate', 0.23)
    
    """Wczytuje config TOML"""
    @staticmethod
    def _load_config(config_file: str) -> Dict:
        config_path = Path(__file__).parent / config_file
        with open(config_path, 'rb') as f:
            return tomllib.load(f)
    
    """Wczytuje JSON z results_dir"""
    def load_json(self, filename: str) -> List:
        with open(self.results_dir / filename, 'r', encoding='utf-8') as f:
            return json.load(f)
    
    """Pobiera wszystkie kategorie z API i buduje dwie mapy: nazw i relacji"""
    def build_category_map(self) -> None:
        print("INFO: Pobieranie wszystkich kategorii z API...")
        
        # Inicjalizujemy dwie mapy, które będziemy budować
        self.category_name_to_id_map = {}
        self.category_parent_map = {}

        response = self.api_client.get_all("categories")
        if response.status_code != 200:
            print(f"ERROR: API kategorii - {response.status_code}")
            return
        
        try:
            root = ET.fromstring(response.content)
            
            # Pobieramy listę ID wszystkich kategorii
            category_ids = [cat.get('id') for cat in root.iter('category') if cat.get('id')]

            print(f"INFO: Znaleziono {len(category_ids)} kategorii. Pobieranie szczegółów...")

            # Pobieramy szczegóły dla każdej kategorii
            for cat_id in category_ids:
                detail_response = self.api_client.get_category(int(cat_id))
                if detail_response.status_code == 200:
                    # Parsujemy odpowiedź, aby dostać ID, nazwę i ID rodzica
                    cat_id_str, cat_name, parent_id_str = self.api_client.parse_category_detail(detail_response)
                    
                    if cat_name and cat_id_str and parent_id_str:
                        # Zapisujemy do mapy nazw: { "Nazwa kategorii": 123 }
                        self.category_name_to_id_map[cat_name] = int(cat_id_str)
                        # Zapisujemy do mapy relacji: { 123: 45 } (ID kategorii -> ID rodzica)
                        self.category_parent_map[int(cat_id_str)] = int(parent_id_str)
            
            print(f"OK: Zbudowano mapy dla {len(self.category_name_to_id_map)} kategorii.")
        except Exception as e:
            print(f"ERROR: Budowanie mapy kategorii - {e}")

    """Importuje produkty - główna funkcja"""
    def import_products(self) -> None:
        print("INFO: Import produktów poprzez REST API...")
        try:
            # Budujemy mapę kategorii
            self.build_category_map()

            # Budujemy mapę atrybutów 
            features_map = self.api_client.get_features_map()

            # Wczytujemy produkty z JSON
            products = self.load_json(self.config['files']['products_file'])
            
            # Ograniczamy do limitu
            limit = self.config['import'].get('products_limit', len(products))
            products = products[:limit]
            
            with ThreadPoolExecutor(max_workers=self.max_workers) as executor:
                futures = [executor.submit(self._import_product, product, features_map) for product in products]
                for idx, future in enumerate(futures):
                    future.result()  # Wyrzuca wyjątek jeśli się pojawił
                    if (idx + 1) % 100 == 0:
                        print(f"INFO: Przetworzono {idx + 1} produktów...")
            
            print(f"OK: Dodano {len(products)} produktów")
        except Exception as e:
            print(f"ERROR: Import produktów - {e}")

    """Importuje pojedynczy produkt"""
    def _import_product(self, product: Dict, features_map: dict) -> None:        
        product_name = product['name']
        product_id = product['id']
        price = product['price']
        category_name = product['category_name']
        description = product['description']
        images = product.get('images', [])

        # Zamiana ceny brutto na netto
        price = round(price / (1 + self.vat_rate), 4)

        # Szukamy ID kategorii na podstawie nazwy
        leaf_category_id = self.category_name_to_id_map.get(category_name)

        if not leaf_category_id:
            print(f"ERROR: Nie znaleziono ID dla kategorii o nazwie '{category_name}'")
            return

        # Tworzymy listę wszystkich kategorii nadrzędnych aż do góry
        category_ids = []
        current_id = leaf_category_id

        # Pętla działa, dopóki mamy poprawne ID i nie doszliśmy do samej góry (ID 0 lub 1)
        while current_id and current_id > 1: 
            category_ids.append(current_id)
            # Szukamy aktualnego rodzica
            current_id = self.category_parent_map.get(current_id)

        # Dodajemy kategorię domyślną z configu, jeśli jej nie ma na liście
        home_category_id = self.config['import'].get('HOME_CATEGORY_ID', 2)
        if home_category_id not in category_ids:
            category_ids.append(home_category_id)

        # Ustawiamy domyślną kategorię na liść
        default_category_id = leaf_category_id

        # Tworzymy cechy dla produktu
        feature_values_map = {} 
        product_attrs = product.get('attributes', {})

        if product_attrs and isinstance(product_attrs, dict):
            for attr_name, attr_value in product_attrs.items():
                if attr_name in features_map:
                    feature_id = features_map[attr_name]
                    
                    # Tworzymy konkretną wartość cechy
                    xml_elem = XMLBuilder.product_feature_value(str(attr_value), feature_id)
                    xml_bytes = XMLBuilder.to_bytes(xml_elem)
                    
                    response = self.api_client.post("product_feature_values", xml_bytes)
                    
                    if response.status_code in (200, 201):
                        fv_id = self.api_client.parse_response(response)
                        feature_values_map[feature_id] = int(fv_id)
                        # print(f"OK: {attr_name}={attr_value} (ID: {fv_id})")
                    elif response.status_code == 409:
                        print(f"INFO: Cecha już istnieje: {attr_name}={attr_value}")
                    else:
                        print(f"BŁĄD: {attr_name}={attr_value} ({response.status_code})")
        else:
            print(f"INFO: Brak atrybutów dla produktu ID {product_id}")

        # Budujemy XML
        xml_elem = XMLBuilder.product(product_name, description, price, product_id, default_category_id, category_ids, feature_values_map)

        # Konwerujemy do wsyłania
        xml_string = ET.tostring(xml_elem, encoding='unicode')

        xml_bytes = xml_string.encode('utf-8')

        response = self.api_client.post("products", xml_bytes)
    
        if response.status_code in API_SUCCESS_CODES:
            prod_id = self.api_client.parse_response(response)
            print(f"INFO: '{product_name}' (ID: {prod_id})")

            # Pobieramy i dodajemy obrazy
            if images:
                process_product_images(prod_id, images, self.config)
            
        else:
            print(f"ERROR: '{product_name}' - {response.status_code}")
            print(f"RESPONSE: {response.text[:500]}")  

if __name__ == "__main__":
    importer = ProductImporter()
    importer.import_products()