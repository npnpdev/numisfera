import json
import tomllib
from pathlib import Path
from typing import List, Dict

from prestashop_base import XMLBuilder, APIClient, API_SUCCESS_CODES, CONFIG_FILE, DEFAULT_PARENT_CATEGORY_ID

class PrestashopImporter:
    def __init__(self, config_file: str = CONFIG_FILE):
        self.config = self._load_config(config_file)
        self.api_client = APIClient(
            self.config['prestashop']['api_url'],
            self.config['prestashop']['api_key'],
            self.config['prestashop'].get('verify_ssl', True)
        )
        self.max_workers = self.config['import'].get('MAX_WORKERS', 5)
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

    """Wczytuje produkty i zbiera wszystkie unikalne ścieżki kategorii"""
    def _get_all_categories_from_product_paths(self) -> set:
        try:
            products = self.load_json(self.config['files']['products_file'])
            required_paths = set()
            
            for product in products:
                if 'category_path' in product and product['category_path']:
                    path = tuple(product['category_path'])
                    # Dla ścieżki (A, B, C) musimy dodać (A,), (A, B) oraz (A, B, C)
                    for i in range(1, len(path) + 1):
                        required_paths.add(path[:i])
                        
            print(f"INFO: Znaleziono {len(required_paths)} unikalnych ścieżek kategorii, które muszą zostać zaimportowane.")
            return required_paths
            
        except FileNotFoundError:
            print(f"ERROR: Plik produktów {self.config['files']['products_file']} nie został znaleziony.")
            return set()
        except Exception as e:
            print(f"ERROR: Nie udało się wczytać kategorii ze ścieżek produktów - {e}")
            return set()

    """Importuje całe drzewo kategorii - główna funkcja"""
    def import_categories(self) -> None:
        print("INFO: Przygotowanie listy kategorii do importu...")
        try:
            # Zbieramy wszystkie kategorie do zaimportowania
            categories_to_import = self._get_all_categories_from_product_paths()
            
            if not categories_to_import:
                print("INFO: Brak kategorii do zaimportowania. Zakończono.")
                return

            # Pobieramy całe drzewo kategorii ze źródła
            all_categories_tree = self.load_json(self.config['files']['categories_file'])
            
            print("\nINFO: Importowanie właściwych kategorii...")
            # Uruchamiamy rekurencyjny import
            self._import_tree(all_categories_tree, DEFAULT_PARENT_CATEGORY_ID, 0, categories_to_import)
            
            print("\nOK: Import kategorii zakończony.")
        except Exception as e:
            print(f"ERROR: Import kategorii - {e}")
    
    """Rekurencyjnie importuje kategorie i podkategorie, sprawdzając pełną ścieżkę"""
    def _import_tree(self, categories: List[Dict], parent_id: int, level: int, required_paths: set, current_path: List[str] = None) -> None:
        if current_path is None:
            current_path = []

        for cat in categories:
            cat_name = cat['name']
            
            # Tworzymy pełną ścieżkę dla aktualnej kategorii
            new_path = current_path + [cat_name]
            
            # SPRAWDZAMY, CZY TA KONKRETNA ŚCIEŻKA JEST NA LIŚCIE WYMAGANYCH
            if tuple(new_path) not in required_paths:
                continue  # Pomiń tę kategorię i całą jej gałąź

            link_rewrite = self._slugify(cat_name)
            indent = "  " * level
                
            xml_elem = XMLBuilder.category(cat_name, link_rewrite, parent_id)
            xml_bytes = XMLBuilder.to_bytes(xml_elem)
            response = self.api_client.post("categories", xml_bytes)
            
            if response.status_code in API_SUCCESS_CODES:
                cat_id = self.api_client.parse_response(response)
                print(f"INFO: {indent}'{cat_name}' (ID: {cat_id})")
                
                if cat.get('children') and cat_id:
                    # Przekazujemy dalej ścieżkę i zbiór wymaganych ścieżek
                    self._import_tree(cat['children'], int(cat_id), level + 1, required_paths, new_path)
            else:
                print(f"ERROR: '{cat_name}' - {response.status_code}")
    
    """Konwerujemy nazwę kategorii na przyjazny link"""
    @staticmethod
    def _slugify(text: str) -> str:
        return text.lower().replace(' ', '-').replace('/', '-')

if __name__ == "__main__":
    importer = PrestashopImporter()
    importer.import_categories()