import json
import tomllib
from pathlib import Path
from typing import List, Dict, Set
import xml.etree.ElementTree as ET
from prestashop_base import XMLBuilder, APIClient, CONFIG_FILE

class AttributeImporter:
    def __init__(self, config_file: str = CONFIG_FILE):
        self.config = self._load_config(config_file)
        self.api_client = APIClient(
            self.config['prestashop']['api_url'],
            self.config['prestashop']['api_key']
        )
        self.results_dir = Path(__file__).parent.parent / self.config['paths']['results_dir']
    
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
                    # Atrybuty to DICT {"Rok Emisji": "2025", ...}
                    for attr_name in product['attributes'].keys():
                        attributes.add(attr_name)
            
            print(f"OK: Znaleziono {len(attributes)} atrybutów")
            for attr in sorted(attributes):
                print(f"  - {attr}")
            return attributes
        
        except Exception as e:
            print(f"ERROR: {e}")
            import traceback
            traceback.print_exc()
            return set()
    
    """Główna funkcja importu atrybutów"""
    def import_attributes(self) -> None:
        print("INFO: Rozpoczynam import atrybutów\n")
        
        # Pobieramy atrybuty
        attributes = self.read_attributes()
        if not attributes:
            print("ERROR: Brak atrybutów!")
            return
        
        # Importujemy atrybuty
        print(f"\nINFO: Import {len(attributes)} atrybutów...\n")
        created = 0
        for attr_name in sorted(attributes):
            xml_elem = XMLBuilder.product_feature(attr_name)
            xml_bytes = XMLBuilder.to_bytes(xml_elem)
            response = self.api_client.post("product_features", xml_bytes)
            
            if response.status_code in (200, 201):
                print(f"OK: {attr_name}")
                created += 1
            elif response.status_code == 409:
                print(f"INFO: {attr_name} (już istnieje)")
            else:
                print(f"ERROR: {attr_name} ({response.status_code})")
        
        print(f"\nOK: Utworzono {created} atrybutów")

if __name__ == "__main__":
    importer = AttributeImporter()
    importer.import_attributes()