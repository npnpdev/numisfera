import json
import tomllib
from pathlib import Path
from typing import List, Dict
import random
import html
import xml.etree.ElementTree as ET
from prestashop_base import XMLBuilder, APIClient, API_SUCCESS_CODES, CONFIG_FILE
from image_downloader import ImageDownloader

"""Importer produktów do PrestaShopa"""
class ProductImporter:
    def __init__(self, config_file: str = CONFIG_FILE):
        self.config = self._load_config(config_file)
        self.api_client = APIClient(
            self.config['prestashop']['api_url'],
            self.config['prestashop']['api_key']
        )
        self.image_downloader = ImageDownloader()
        self.results_dir = Path(__file__).parent.parent / self.config['paths']['results_dir']
        self.category_map = {}
    
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
    
    """Buduje mapę z załadowanych kategorii JSON"""
    def build_category_map(self) -> None:
        """Pobiera WSZYSTKIE kategorie z API - z pełną ścieżką"""
        print("INFO: Pobieranie wszystkich kategorii z API...")
        response = self.api_client.get_all_categories()
        if response.status_code != 200:
            print(f"ERROR: API kategorii - {response.status_code}")
            return
        
        try:
            root = ET.fromstring(response.content)
            
            for cat_elem in root.iter('category'):
                cat_id = cat_elem.get('id')
                if cat_id:
                    detail_response = self.api_client.get_category(int(cat_id))
                    if detail_response.status_code == 200:
                        cat_id_str, cat_name = self.api_client.parse_category_detail(detail_response)
                        if cat_name and cat_id_str:
                            # Klucz: ID (unikatowe)
                            self.category_map[int(cat_id_str)] = cat_name
            
            print(f"OK: Załadowano {len(self.category_map)} kategorii")
        except Exception as e:
            print(f"ERROR: Budowanie mapy kategorii - {e}")

    """Importuje produkty - główna funkcja"""
    def import_products(self) -> None:
        print("ETAP 2: Import produktów poprzez REST API...")
        try:
            self.build_category_map()
            products = self.load_json(self.config['files']['products_file'])
            
            # Ograniczamy do limitu
            limit = self.config['import'].get('products_limit', len(products))
            products = products[:limit]
            
            for idx, product in enumerate(products):
                self._import_product(product)
                if (idx + 1) % 100 == 0:
                    print(f"INFO: Przetworzono {idx + 1} produktów...")
            
            print(f"OK: Dodano {len(products)} produktów")
        except Exception as e:
            print(f"ERROR: Import produktów - {e}")

    """Importuje pojedynczy produkt"""
    def _import_product(self, product: Dict) -> None:        
        product_name = product['name']
        product_id = product['id']
        price = product['price']
        category_name = product['category_name']
        description = product['description']
        images = product.get('images', [])
        
        # Szukamy ID kategorii
        category_id = None
        for cat_id, cat_name in self.category_map.items():
            if cat_name == category_name:
                category_id = cat_id
                break
        
        if not category_id:
            print(f"ERROR: Nie znaleziono kategorii '{category_name}'")
            return
        
        # Czyścimy HTML z opisu
        clean_description = html.unescape(description)
        
        # Pobieramy i zapisujemy obrazy lokalnie
        local_images = self.image_downloader.download_product_images(product_id, images)
        # Losowa ilość na magazynie
        quantity = random.randint(1, 10)
        
        # Budujemy XML
        xml_elem = XMLBuilder.product(product_name, clean_description, price, product_id, category_id)
        
        # Konwertujemy
        xml_string = ET.tostring(xml_elem, encoding='unicode')
        xml_string = xml_string.replace('&lt;', '<').replace('&gt;', '>').replace('&quot;', '"')
        xml_bytes = xml_string.encode('utf-8')
                
        response = self.api_client.post_product(xml_bytes)
    
        if response.status_code in API_SUCCESS_CODES:
            prod_id = self.api_client.parse_response(response)
            print(f"INFO: '{product_name}' (ID: {prod_id})")
        else:
            print(f"ERROR: '{product_name}' - {response.status_code}")
            print(f"RESPONSE: {response.text[:500]}")  

if __name__ == "__main__":
    importer = ProductImporter()
    importer.import_products()