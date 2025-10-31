import json
import requests
import tomllib
from pathlib import Path
from requests.auth import HTTPBasicAuth
import xml.etree.ElementTree as ET
from typing import Optional, List, Dict

# Konfiguracja
CONFIG_FILE = 'config.toml'
DEFAULT_LANGUAGE_ID = '1' # Język polski
DEFAULT_PARENT_CATEGORY_ID = 2 # Kategoria główna domyślna w PrestaShop - "Strona główna"
PRESTASHOP_NAMESPACE = 'http://www.w3.org/1999/xlink'
API_SUCCESS_CODES = (200, 201) 
API_TIMEOUT = 30 # Jeżeli API nie odpowiada w tym czasie, przerywamy

"""Buduje XML'a dla PrestaShopa"""
class XMLBuilder:
    """Buduje XML kategorii"""
    @staticmethod 
    def category(name: str, link_rewrite: str, parent_id: int) -> ET.Element:
        root = ET.Element('prestashop')
        root.set('xmlns:xlink', PRESTASHOP_NAMESPACE)
        
        cat = ET.SubElement(root, 'category')
        
        # Nazwa kategorii
        name_elem = ET.SubElement(cat, 'name')
        lang = ET.SubElement(name_elem, 'language', id=DEFAULT_LANGUAGE_ID)
        lang.text = name
        
        # Ustawienie prawidłowego linku
        link_elem = ET.SubElement(cat, 'link_rewrite')
        lang2 = ET.SubElement(link_elem, 'language', id=DEFAULT_LANGUAGE_ID)
        lang2.text = link_rewrite
        
        # Ustawiamy kategorie jako aktywną i przypisujemy rodzica
        ET.SubElement(cat, 'active').text = '1'
        ET.SubElement(cat, 'id_parent').text = str(parent_id)
        
        return root
    
    """Konwertuje XML na tekst bajtowy wymagany przez prestashop API"""
    @staticmethod
    def to_bytes(element: ET.Element) -> bytes:
        return ET.tostring(element, encoding='utf-8')

"""Komunikacja z PrestaShop API"""
class APIClient:
    def __init__(self, api_url: str, api_key: str):
        self.api_url = api_url
        self.api_key = api_key
        self.session = requests.Session()
    
    """Wysyła kategorię do API"""
    def post_category(self, xml_payload: bytes) -> requests.Response:
        return self.session.post(
            f"{self.api_url}/categories",
            data=xml_payload,
            headers={'Content-Type': 'application/xml'},
            auth=HTTPBasicAuth(self.api_key, ''),
            timeout=API_TIMEOUT
        )
    
    """Wyciąga ID kategorii z odpowiedzi"""
    def parse_response(self, response: requests.Response) -> Optional[str]:
        try:
            root = ET.fromstring(response.content)
            cat_elem = root.find('category')
            if cat_elem is not None:
                id_elem = cat_elem.find('id')
                return id_elem.text if id_elem is not None else None
        except Exception as e:
            print(f"ERROR: Parsowanie odpowiedzi - {e}")
        return None

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