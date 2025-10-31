import json
import tomllib
from pathlib import Path
from typing import List, Dict

from prestashop_base import XMLBuilder, APIClient, API_SUCCESS_CODES, CONFIG_FILE, DEFAULT_PARENT_CATEGORY_ID

"""Importer kategorii do PrestaShopa"""
class PrestashopImporter:
    def __init__(self, config_file: str = CONFIG_FILE):
        self.config = self._load_config(config_file)
        self.api_client = APIClient(
            self.config['prestashop']['api_url'],
            self.config['prestashop']['api_key']
        )
        self.results_dir = Path(__file__).parent.parent / self.config['paths']['results_dir']
    
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
    
    """Importuje całe drzewo kategorii - główna funkcja"""
    def import_categories(self) -> None:
        print("ETAP 1: Import kategorii i podkategorii poprzez REST API...")
        try:
            categories = self.load_json(self.config['files']['categories_file'])
            self._import_tree(categories, parent_id=DEFAULT_PARENT_CATEGORY_ID, level=0)
            print("OK: Dodano kategorie")
        except Exception as e:
            print(f"ERROR: Import kategorii - {e}")
    
    """Rekurencyjnie importuje kategorie i podkategorie - funkcja pomocnicza"""
    def _import_tree(self, categories: List[Dict], parent_id: int, level: int) -> None:
        for cat in categories:
            cat_name = cat['name']
            link_rewrite = self._slugify(cat_name)
            indent = "  " * level
            
            # Budujemy XML i wysyłamy do API
            xml_elem = XMLBuilder.category(cat_name, link_rewrite, parent_id)
            xml_bytes = XMLBuilder.to_bytes(xml_elem)
            response = self.api_client.post_category(xml_bytes)
            
            # Sprawdzamy odpowiedź
            if response.status_code in API_SUCCESS_CODES:
                cat_id = self.api_client.parse_response(response)
                print(f"INFO: {indent}'{cat_name}' (ID: {cat_id})")
                
                # Importujemy dzieci
                if cat.get('children') and cat_id:
                    self._import_tree(cat['children'], parent_id=int(cat_id), level=level+1)
            else:
                print(f"ERROR: '{cat_name}' - {response.status_code}")
    
    """Konwerujemy nazwę kategorii na przyjazny link"""
    @staticmethod
    def _slugify(text: str) -> str:
        return text.lower().replace(' ', '-').replace('/', '-')

if __name__ == "__main__":
    importer = PrestashopImporter()
    importer.import_categories()