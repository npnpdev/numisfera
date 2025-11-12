import requests
import urllib3
import xml.etree.ElementTree as ET
from typing import Optional
from requests.auth import HTTPBasicAuth

# Globalne
CONFIG_FILE = 'config.toml'
DEFAULT_PARENT_CATEGORY_ID = 2 # ID głównej kategorii "Root" w PrestaShop
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
    def product(name: str, description: str, price: float, sku: str, default_category_id: int, category_ids: list, feature_values_ids: dict = None) -> ET.Element:
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
        ET.SubElement(prod, 'id_category_default').text = str(default_category_id)

        name_elem = ET.SubElement(prod, 'name')
        lang = ET.SubElement(name_elem, 'language', id=DEFAULT_LANGUAGE_ID)
        lang.text = name
        
        desc_elem = ET.SubElement(prod, 'description')
        lang_desc = ET.SubElement(desc_elem, 'language', id=DEFAULT_LANGUAGE_ID)
        lang_desc.text = f"{description}"
        
        assoc = ET.SubElement(prod, 'associations')
        cats = ET.SubElement(assoc, 'categories')

        # Tworzymy pętlę, która doda wszystkie kategorie z listy
        for cat_id in category_ids:
            cat = ET.SubElement(cats, 'category')
            ET.SubElement(cat, 'id').text = str(cat_id)
        
        # Atrybuty produktu
        if feature_values_ids:
            fv_assoc = ET.SubElement(assoc, 'product_features')
            for feature_id, fv_id in feature_values_ids.items(): 
                fv_elem = ET.SubElement(fv_assoc, 'product_feature')
                ET.SubElement(fv_elem, 'id').text = str(feature_id)
                ET.SubElement(fv_elem, 'id_feature_value').text = str(fv_id)
    
        return root

    """Buduje XML dla atrybutu - wykorzystywane przy imporcie cech"""
    @staticmethod
    def product_feature(name: str) -> ET.Element:
        root = ET.Element('prestashop')
        root.set('xmlns:xlink', PRESTASHOP_NAMESPACE)
        
        feat = ET.SubElement(root, 'product_feature')
        name_elem = ET.SubElement(feat, 'name')
        lang = ET.SubElement(name_elem, 'language', id=DEFAULT_LANGUAGE_ID)
        lang.text = name
        
        return root

    """Buduje XML dla wartości atrybutu - wykorzystywane przy imporcie produktów"""
    @staticmethod
    def product_feature_value(value: str, feature_id: int) -> ET.Element:
        root = ET.Element('prestashop')
        root.set('xmlns:xlink', PRESTASHOP_NAMESPACE)
        
        fv = ET.SubElement(root, 'product_feature_value')
        ET.SubElement(fv, 'id_feature').text = str(feature_id)
        ET.SubElement(fv, 'custom').text = '0'
        
        value_elem = ET.SubElement(fv, 'value')
        lang = ET.SubElement(value_elem, 'language', id=DEFAULT_LANGUAGE_ID)
        lang.text = value
        
        return root

    """Zamienia XML na bajty do wysłania przez API"""
    @staticmethod
    def to_bytes(element: ET.Element) -> bytes:
        return ET.tostring(element, encoding='utf-8')

"""Komunikacja z PrestaShop API"""
class APIClient:
    def __init__(self, api_url: str, api_key: str, verify_ssl: bool = True):
        self.api_url = api_url
        self.api_key = api_key
        self.session = requests.Session()
        self.verify_ssl = verify_ssl

        # Wyłączamy ostrzeżenia SSL jeśli weryfikacja jest wyłączona
        if not verify_ssl:
            urllib3.disable_warnings(urllib3.exceptions.InsecureRequestWarning)

    """Wyciąga ID z odpowiedzi API"""
    def parse_response(self, response: requests.Response) -> Optional[str]:
        try:
            root = ET.fromstring(response.content)
            elem = root.find('category') or root.find('product') or root.find('product_feature') or root.find('product_feature_value')
            if elem is not None:
                id_elem = elem.find('id')
                return id_elem.text if id_elem is not None else None
        except Exception as e:
            print(f"ERROR: Parsowanie odpowiedzi - {e}")
        return None

    """Wysyła zasób do danego endpointu"""
    def post(self, endpoint: str, xml_payload: bytes) -> requests.Response:
        return self.session.post(
            f"{self.api_url}/{endpoint}",
            data=xml_payload,
            headers={'Content-Type': 'application/xml'},
            auth=HTTPBasicAuth(self.api_key, ''),
            timeout=API_TIMEOUT,
            verify=self.verify_ssl
        )

    """Wysyła zaktualizowany zasób do danego endpointu metodą PUT"""
    def put(self, endpoint: str, xml_payload: bytes) -> requests.Response:
        # Adres URL dla PUT musi zawierać ID zasobu, które jest w XML-u
        resource_id = ET.fromstring(xml_payload).find(f'.//{endpoint[:-1]}/id').text
        
        return self.session.put(
            f"{self.api_url}/{endpoint}/{resource_id}",
            data=xml_payload,
            headers={'Content-Type': 'application/xml'},
            auth=HTTPBasicAuth(self.api_key, ''),
            timeout=API_TIMEOUT,
            verify=self.verify_ssl
        )

    """Pobiera wszystkie zasoby z danego endpointu"""
    def get_all(self, endpoint: str) -> requests.Response:
        return self.session.get(
            f"{self.api_url}/{endpoint}",
            headers={'Content-Type': 'application/xml'},
            auth=HTTPBasicAuth(self.api_key, ''),
            timeout=API_TIMEOUT,
            verify=self.verify_ssl
        )

    """Pobiera szczegóły jednej kategorii"""
    def get_category(self, category_id: int) -> requests.Response:
        return self.get_all(f"categories/{category_id}")
    
    """Parsuje szczegóły kategorii: (id, name, id_parent)"""
    def parse_category_detail(self, response: requests.Response) -> tuple:    
        try:
            root = ET.fromstring(response.content)
            cat_elem = root.find('category')
            if cat_elem is not None:
                id_elem = cat_elem.find('id')
                parent_id_elem = cat_elem.find('id_parent')
                name_elem = cat_elem.find('name')
                if id_elem is not None and name_elem is not None and parent_id_elem is not None:
                    lang_elem = name_elem.find('language')
                    if lang_elem is not None:
                        # Zwracamy ID, nazwę i ID rodzica
                        return (id_elem.text, lang_elem.text, parent_id_elem.text)
        except Exception as e:
            print(f"ERROR: Parsowanie szczegółów kategorii - {e}")
        return (None, None, None)

    """Pobiera mapę wszystkich product_features (atrybutów): {nazwa → id}"""
    def get_features_map(self) -> dict:
        features_map = {}
        try:
            response = self.get_all("product_features")
            if response.status_code == 200:
                root = ET.fromstring(response.content)
                for feat_elem in root.iter('product_feature'):
                    feat_id = feat_elem.get('id')
                    if feat_id:
                        detail_resp = self.get_all(f"product_features/{feat_id}")

                        if detail_resp.status_code == 200:
                            detail_root = ET.fromstring(detail_resp.content)
                            name_elem = detail_root.find('.//product_feature/name/language')
                            if name_elem is not None and name_elem.text:
                                features_map[name_elem.text] = int(feat_id)
        except Exception as e:
            print(f"ERROR: Pobieranie mapy features - {e}")
        return features_map
