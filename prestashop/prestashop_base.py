import requests
import xml.etree.ElementTree as ET
from typing import Optional
from requests.auth import HTTPBasicAuth

# Globalne
CONFIG_FILE = 'config.toml'
DEFAULT_PARENT_CATEGORY_ID = 2
DEFAULT_LANGUAGE_ID = '1'
PRESTASHOP_NAMESPACE = 'http://www.w3.org/1999/xlink'
API_SUCCESS_CODES = (200, 201)
API_TIMEOUT = 30

"""Buduje XML dla PrestaShopa (kategorie i produkty)"""
class XMLBuilder:
    """Buduje XML kategorii"""
    @staticmethod
    def category(name: str, link_rewrite: str, parent_id: int) -> ET.Element:
        root = ET.Element('prestashop')
        root.set('xmlns:xlink', PRESTASHOP_NAMESPACE)
        
        cat = ET.SubElement(root, 'category')
        
        name_elem = ET.SubElement(cat, 'name')
        lang = ET.SubElement(name_elem, 'language', id=DEFAULT_LANGUAGE_ID)
        lang.text = name
        
        link_elem = ET.SubElement(cat, 'link_rewrite')
        lang2 = ET.SubElement(link_elem, 'language', id=DEFAULT_LANGUAGE_ID)
        lang2.text = link_rewrite
        
        ET.SubElement(cat, 'active').text = '1'
        ET.SubElement(cat, 'id_parent').text = str(parent_id)
        
        return root
    
    """Buduje XML produktu""" 
    @staticmethod
    def product(name: str, description: str, price: float, sku: str, category_id: int) -> ET.Element:
        root = ET.Element('prestashop')
        root.set('xmlns:xlink', PRESTASHOP_NAMESPACE)
        
        prod = ET.SubElement(root, 'product')
        
        ET.SubElement(prod, 'id_tax_rules_group').text = '1'
        ET.SubElement(prod, 'id_manufacturer').text = '1'
        ET.SubElement(prod, 'id_supplier').text = '1'
        ET.SubElement(prod, 'reference').text = sku
        ET.SubElement(prod, 'price').text = str(price)
        ET.SubElement(prod, 'type').text = 'simple'
        ET.SubElement(prod, 'id_shop_default').text = '1'
        ET.SubElement(prod, 'active').text = '1'
        ET.SubElement(prod, 'available_for_order').text = '1'
        ET.SubElement(prod, 'show_price').text = '1'
        ET.SubElement(prod, 'state').text = '1'           
        ET.SubElement(prod, 'pack_stock_type').text = '3' 
        ET.SubElement(prod, 'id_category_default').text = str(category_id)
        
        name_elem = ET.SubElement(prod, 'name')
        lang = ET.SubElement(name_elem, 'language', id='1')
        lang.text = name
        
        desc_elem = ET.SubElement(prod, 'description')
        lang_desc = ET.SubElement(desc_elem, 'language', id='1')
        lang_desc.text = description
        
        # DODAJ ASSOCIATIONS!
        assoc = ET.SubElement(prod, 'associations')
        cats = ET.SubElement(assoc, 'categories')
        cat = ET.SubElement(cats, 'category')
        ET.SubElement(cat, 'id').text = str(category_id)
        
        return root

    """Zamienia XML na bytes"""
    @staticmethod
    def to_bytes(element: ET.Element) -> bytes:
        return ET.tostring(element, encoding='utf-8')

"""Komunikacja z PrestaShop API"""
class APIClient:
    def __init__(self, api_url: str, api_key: str):
        self.api_url = api_url
        self.api_key = api_key
        self.session = requests.Session()
    
    def post_category(self, xml_payload: bytes) -> requests.Response:
        return self.session.post(
            f"{self.api_url}/categories",
            data=xml_payload,
            headers={'Content-Type': 'application/xml'},
            auth=HTTPBasicAuth(self.api_key, ''),
            timeout=API_TIMEOUT
        )
    
    def post_product(self, xml_payload: bytes) -> requests.Response:
        return self.session.post(
            f"{self.api_url}/products",
            data=xml_payload,
            headers={'Content-Type': 'application/xml'},
            auth=HTTPBasicAuth(self.api_key, ''),
            timeout=API_TIMEOUT
        )
    
    """Wyciąga ID z odpowiedzi API"""
    def parse_response(self, response: requests.Response) -> Optional[str]:
        try:
            root = ET.fromstring(response.content)
            elem = root.find('category') or root.find('product')
            if elem is not None:
                id_elem = elem.find('id')
                return id_elem.text if id_elem is not None else None
        except Exception as e:
            print(f"ERROR: Parsowanie odpowiedzi - {e}")
        return None

    """Pobiera wszystkie kategorie z API"""
    def get_all_categories(self) -> requests.Response:
        return self.session.get(
            f"{self.api_url}/categories",
            headers={'Content-Type': 'application/xml'},
            auth=HTTPBasicAuth(self.api_key, ''),
            timeout=API_TIMEOUT
        )
    
    """Parsuje odpowiedź z kategorii i buduje mapę name -> id"""
    def parse_categories_response(self, response: requests.Response) -> dict:    
        category_map = {}
        try:
            root = ET.fromstring(response.content)
            print(f"DEBUG: Otrzymano XML: {response.content[:500]}")  
            for cat_elem in root.findall('category'):
                id_elem = cat_elem.find('id')
                name_elem = cat_elem.find('name')
                if id_elem is not None and name_elem is not None:
                    lang_elem = name_elem.find('language')
                    if lang_elem is not None:
                        cat_id = id_elem.text
                        cat_name = lang_elem.text
                        category_map[cat_name] = int(cat_id)
        except Exception as e:
            print(f"ERROR: Parsowanie kategorii - {e}")
        return category_map

    """Pobiera szczegóły jednej kategorii"""
    def get_category(self, category_id: int) -> requests.Response:
        return self.session.get(
            f"{self.api_url}/categories/{category_id}",
            headers={'Content-Type': 'application/xml'},
            auth=HTTPBasicAuth(self.api_key, ''),
            timeout=API_TIMEOUT
        )
    
    """Parsuje szczegóły kategorii: (id, name)"""
    def parse_category_detail(self, response: requests.Response) -> tuple:    
        try:
            root = ET.fromstring(response.content)
            cat_elem = root.find('category')
            if cat_elem is not None:
                id_elem = cat_elem.find('id')
                name_elem = cat_elem.find('name')
                if id_elem is not None and name_elem is not None:
                    lang_elem = name_elem.find('language')
                    if lang_elem is not None:
                        return (id_elem.text, lang_elem.text)
        except Exception as e:
            print(f"ERROR: Parsowanie szczegółów kategorii - {e}")
        return (None, None)

