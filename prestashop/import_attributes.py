import json
import tomllib
from pathlib import Path
from typing import List, Dict, Set
from prestashop_base import XMLBuilder, APIClient, CONFIG_FILE
from concurrent.futures import ThreadPoolExecutor

class AttributeImporter:
    def __init__(self, config_file: str = CONFIG_FILE):
        self.config = self._load_config(config_file)
        self.api_client = APIClient(
            self.config['prestashop']['api_url'],
            self.config['prestashop']['api_key'],
            self.config['prestashop'].get('verify_ssl', True)
        )
        self.results_dir = Path(__file__).parent.parent / self.config['paths']['results_dir']
        self.max_workers = self.config['import'].get('MAX_WORKERS', 5)
    
    @staticmethod
    def _load_config(config_file: str) -> Dict:
        config_path = Path(__file__).parent / config_file
        with open(config_path, 'rb') as f:
            return tomllib.load(f)
    
    def load_json(self, filename: str) -> List:
        with open(self.results_dir / filename, 'r', encoding='utf-8') as f:
            return json.load(f)
    
    """Pobieramy wszystkie atrybuty z produktów"""
    def read_attributes(self) -> Set[str]:
        print("INFO: Pobieranie atrybutów...")
        try:
            products = self.load_json(self.config['files']['products_file'])
            attributes = set()
            
            for product in products:
                if 'attributes' in product and isinstance(product['attributes'], dict):
                    # Atrybuty to DICT {"Atrybut": "Atrybut", ...}
                    for attr_name in product['attributes'].keys():
                        attributes.add(attr_name)
            
            print(f"OK: Znaleziono {len(attributes)} atrybutów")
            for attr in sorted(attributes):
                print(f"  - {attr}")
            return attributes
        
        except Exception as e:
            print(f"ERROR: {e}")
            return set()
    
    """Wysyła jeden atrybut do API i zwraca status operacji"""
    def _import_single_attribute(self, attr_name: str) -> str:
        xml_elem = XMLBuilder.product_feature(attr_name)
        xml_bytes = XMLBuilder.to_bytes(xml_elem)
        response = self.api_client.post("product_features", xml_bytes)
        
        if response.status_code in (200, 201):
            print(f"OK: {attr_name}")
            return "created"
        elif response.status_code == 409:
            print(f"INFO: {attr_name} (już istnieje)")
            return "skipped"
        else:
            print(f"ERROR: {attr_name} ({response.status_code})")
            return "error"

    """Główna funkcja importu atrybutów"""
    def import_attributes(self) -> None:
        print("INFO: Rozpoczynam import atrybutów\n")
        
        attributes = self.read_attributes()
        if not attributes:
            print("ERROR: Brak atrybutów do zaimportowania!")
            return

        print(f"\nINFO: Importuję {len(attributes)} atrybutów...\n")

        sorted_attributes = sorted(list(attributes))
        
        with ThreadPoolExecutor(max_workers=self.max_workers) as executor:
            results = list(executor.map(self._import_single_attribute, sorted_attributes))

        created_count = results.count("created")
        skipped_count = results.count("skipped")
        error_count = results.count("error")
        
        print(f"INFO: Nowo utworzone: {created_count}")
        print(f"INFO: Pominięte (istniejące): {skipped_count}")
        print(f"ERRORS: {error_count}")

if __name__ == "__main__":
    importer = AttributeImporter()
    importer.import_attributes()