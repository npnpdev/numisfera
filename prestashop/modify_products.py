import tomllib
from pathlib import Path
from typing import Dict
import xml.etree.ElementTree as ET
import random

from prestashop_base import APIClient, CONFIG_FILE

"""Klasa odpowiedzialna za modyfikacje produktów po imporcie"""
class ProductModifier:
    def __init__(self, config_file: str = CONFIG_FILE):
        self.config = self._load_config(config_file)
        self.api_client = APIClient(
            self.config['prestashop']['api_url'],
            self.config['prestashop']['api_key'],
            self.config['prestashop'].get('verify_ssl', True)
        )

    @staticmethod
    def _load_config(config_file: str) -> Dict:
        config_path = Path(__file__).parent / config_file
        with open(config_path, 'rb') as f:
            return tomllib.load(f)

    """Znajduje produkty i ustawia im wagę."""
    def set_heavy_products(self) -> None:
        print("INFO: Rozpoczynanie ustawiania wagi dla produktów...")
        
        # Pobieramy listę wszystkich produktów
        products_response = self.api_client.get_all("products")
        if products_response.status_code != 200:
            print(f"ERROR: Nie udało się pobrać listy produktów. Status: {products_response.status_code}")
            return

        # Wyciągamy ID produktów z odpowiedzi
        try:            
            root = ET.fromstring(products_response.content)
            product_ids = [int(p.attrib['id']) for p in root.iter('product') if 'id' in p.attrib]
            print(f"INFO: Znaleziono {len(product_ids)} produktów. Przetwarzanie...")
        except Exception as e:
            print(f"ERROR: Błąd podczas parsowania listy ID produktów: {e}")
            return

        # Pobieramy stany magazynowe dla wszystkich produktów
        stock_response = self.api_client.get_all("stock_availables?display=full")
        if stock_response.status_code != 200:
            print(f"ERROR: Nie udało się pobrać stanów magazynowych. Status: {stock_response.status_code}")
            return
        
        # Tworzymy mapę ID produktu -> ilość
        stock_map = {}
        try:
            root = ET.fromstring(stock_response.content)
            for stock in root.iter('stock_available'):
                product_id_elem = stock.find('id_product')
                quantity_elem = stock.find('quantity')
                if product_id_elem is not None and quantity_elem is not None:
                    product_id = int(product_id_elem.text)
                    quantity = int(quantity_elem.text)
                    stock_map[product_id] = quantity
        except Exception as e:
            print(f"ERROR: Parsowanie stanów magazynowych - {e}")
            return

        # Szukamy produktów z ilością > progu z configu
        heavy_product_candidates = []
        for pid in product_ids:
            quantity = stock_map.get(pid, 0)
            if quantity > self.config['modifications'].get('heavy_product_threshold', 5):
                heavy_product_candidates.append(pid)
                if len(heavy_product_candidates) >= self.config['modifications'].get('max_heavy_products', 10):
                    break

        if not heavy_product_candidates:
            print(f"INFO: Nie znaleziono wystarczającej liczby produktów (stan > {self.config['modifications'].get('heavy_product_threshold', 5)}) do modyfikacji.")
            return

        print(f"OK: Znaleziono {len(heavy_product_candidates)} produktów ze stanem > {self.config['modifications'].get('heavy_product_threshold', 5)}. Kandydaci do zmiany wagi: {heavy_product_candidates}")

        # Aktualizujemy wagę dla znalezionych produktów        
        for product_id in heavy_product_candidates:
            # Pobieramy pełny XML produktu
            get_response = self.api_client.get_all(f"products/{product_id}")
            if get_response.status_code != 200:
                print(f"ERROR: Nie można pobrać produktu {product_id} do aktualizacji.")
                continue

            try:
                product_xml = ET.fromstring(get_response.content)
                product_node = product_xml.find('product')
                if product_node is None:
                    print(f"ERROR: Nie znaleziono węzła <product> w odpowiedzi dla ID {product_id}")
                    continue

                # Ustawiamy nową wagę
                weight_element = product_node.find('weight')
                new_weight = str(self.config['modifications'].get('heavy_product_weight', 20.0))
                if weight_element is not None:
                    weight_element.text = new_weight
                else:
                    ET.SubElement(product_node, 'weight').text = new_weight

                # Usuwamy pola tylko do odczytu
                fields_to_remove = ['manufacturer_name', 'quantity', 'position_in_category', 'date_add', 'date_upd', 'associations']
                
                for field_name in fields_to_remove:
                    element_to_remove = product_node.find(field_name)
                    if element_to_remove is not None:
                        product_node.remove(element_to_remove)

                # Wysyłamy zaktualizowany XML
                updated_xml_bytes = ET.tostring(product_xml, encoding='utf-8')
                put_response = self.api_client.put("products", updated_xml_bytes)
                
                if put_response.status_code == 200:
                    print(f"OK: Pomyślnie zaktualizowano wagę dla produktu ID: {product_id}")
                else:
                    print(f"ERROR: Aktualizacja produktu {product_id} nie powiodła się. Status: {put_response.status_code}")

            except Exception as e:
                print(f"ERROR: Wystąpił błąd podczas przetwarzania produktu {product_id}: {e}")

    """Tworzy promocje dla wybranych produktów"""
    def create_promotions(self) -> None:
        print("INFO: Rozpoczynanie tworzenia promocji cenowych...")
        
        promo_count = self.config['modifications'].get('promotion_product_count', 5)

        # Pobieramy listę wszystkich produktów
        products_response = self.api_client.get_all("products")
        if products_response.status_code != 200:
            print(f"ERROR: Nie udało się pobrać listy produktów. Status: {products_response.status_code}")
            return

        try:
            root = ET.fromstring(products_response.content)
            all_product_ids = [int(p.attrib['id']) for p in root.iter('product') if 'id' in p.attrib]
        except Exception as e:
            print(f"ERROR: Błąd podczas parsowania listy ID produktów: {e}")
            return

        if not all_product_ids:
            print("INFO: Nie znaleziono żadnych produktów.")
            return

        # Losujemy "promo_count" produktów do promocji
        if len(all_product_ids) < promo_count:
            print(f"WARNING: Liczba produktów ({len(all_product_ids)}) jest mniejsza niż docelowa liczba promocji ({promo_count}). Wszystkie produkty zostaną objęte promocją.")
            products_for_promo = all_product_ids
        else:
            products_for_promo = random.sample(all_product_ids, promo_count)

        print(f"INFO: Wybrano {len(products_for_promo)} produktów do utworzenia promocji: {products_for_promo}")

        # Pobieramy wartość zniżki z konfiguracji
        reduction = self.config['modifications'].get('promotion_reduction_percentage', 0.15)
        
        print(f"INFO: Tworzenie {len(products_for_promo)} promocji ze zniżką {reduction*100:.0f}%...")

        # Iterujemy po wybranych produktach i tworzymy dla nich promocje
        for product_id in products_for_promo:
            try:
                # Tworzymy XML dla nowej ceny
                root = ET.Element('prestashop')
                sp = ET.SubElement(root, 'specific_price')
                
                ET.SubElement(sp, 'id_product').text = str(product_id)
                ET.SubElement(sp, 'id_shop').text = '0'
                ET.SubElement(sp, 'id_cart').text = '0'
                ET.SubElement(sp, 'id_customer').text = '0'
                ET.SubElement(sp, 'id_currency').text = '0'
                ET.SubElement(sp, 'id_country').text = '0'
                ET.SubElement(sp, 'id_group').text = '0'
                ET.SubElement(sp, 'price').text = '-1'
                ET.SubElement(sp, 'from_quantity').text = '1'
                ET.SubElement(sp, 'reduction').text = str(reduction)
                ET.SubElement(sp, 'reduction_tax').text = '1'
                ET.SubElement(sp, 'reduction_type').text = 'percentage'
                ET.SubElement(sp, 'from').text = '0000-00-00 00:00:00'
                ET.SubElement(sp, 'to').text = '0000-00-00 00:00:00'
                
                xml_bytes = ET.tostring(root, encoding='utf-8')

                # Wysyłamy promocję do sklepu
                response = self.api_client.post("specific_prices", xml_bytes)
                
                if response.status_code in (200, 201):
                    print(f"OK: Utworzono promocję dla produktu ID: {product_id}")
                else:
                    print(f"ERROR: Nie udało się utworzyć promocji dla ID {product_id}. Status: {response.status_code}")
            except Exception as e:
                print(f"ERROR: Krytyczny błąd podczas przetwarzania produktu ID {product_id}: {e}")


    """Tworzy warianty dla produktów"""
    def create_variants(self) -> None:
        
        variants_config = self.config['modifications']['variants']
        group_name = variants_config['attribute_group_name']
        attribute_group_id = None
        
        # Tworzymy nową grupę atrybutów
        try:
            print(f"INFO: Tworzenie nowej grupy atrybutów '{group_name}'...")
            root = ET.Element('prestashop')
            option_node = ET.SubElement(root, 'product_option')
            ET.SubElement(option_node, 'group_type').text = 'select'
            name_node = ET.SubElement(option_node, 'name')
            ET.SubElement(name_node, 'language', id='1').text = group_name
            public_name_node = ET.SubElement(option_node, 'public_name')
            ET.SubElement(public_name_node, 'language', id='1').text = group_name
            xml_bytes = ET.tostring(root, encoding='utf-8')
            
            response = self.api_client.post("product_options", xml_bytes)

            if response.status_code == 201:
                response_root = ET.fromstring(response.content)
                id_elem = response_root.find('.//product_option/id')
                if id_elem is not None and id_elem.text:
                    attribute_group_id = int(id_elem.text)
                    print(f"OK: Utworzono grupę. ID: {attribute_group_id}")
                else:
                    print("ERROR: Nie znaleziono ID nowo utworzonej grupy atrybutów.")
                    return
            else:
                print(f"ERROR: Nie udało się utworzyć grupy atrybutów. Status: {response.status_code}, {response.text}")
                return
        except (ValueError, ConnectionError, ET.ParseError) as e:
            print(f"ERROR: [Warianty] {e}")
            return

        # Tworzymy nowe wartości dla tej grupy
        value_id_map = {}
        print(f"INFO: Tworzenie wartości dla grupy ID: {attribute_group_id}...")

        for value_config in variants_config['attributes']:
            value_name = value_config['name']
            try:
                root = ET.Element('prestashop')
                value_node = ET.SubElement(root, 'product_option_value')
                ET.SubElement(value_node, 'id_attribute_group').text = str(attribute_group_id)
                name_node = ET.SubElement(value_node, 'name')
                ET.SubElement(name_node, 'language', id='1').text = value_name
                xml_bytes = ET.tostring(root, encoding='utf-8')

                response = self.api_client.post("product_option_values", xml_bytes)

                if response.status_code == 201:
                    response_root = ET.fromstring(response.content)
                    id_elem = response_root.find('.//product_option_value/id')
                    if id_elem is not None and id_elem.text:
                        new_id = int(id_elem.text)
                        value_id_map[value_name] = new_id
                        print(f"OK: Utworzono wartość '{value_name}' (ID: {new_id})")
                    else:
                        print(f"ERROR: Nie znaleziono ID dla wartości '{value_name}'.")
                        continue # Kontynuujemy pętlę nawet jeśli jedna wartość się nie uda
                else:
                    print(f"ERROR: Nie udało się utworzyć wartości '{value_name}'. Status: {response.status_code}, {response.text}")
                    continue
            except (ValueError, ConnectionError, ET.ParseError) as e:
                print(f"ERROR: {e}")
                continue
        
        if len(value_id_map) != len(variants_config['attributes']):
            print("ERROR: Nie udało się utworzyć wszystkich wartości atrybutów. Przerwanie.")
            return

        print(f"OK: Przygotowano mapę ID wartości: {value_id_map}")

        # Wybieramy "variants_to_create" produktów do modyfikacji
        num_products_to_modify = variants_config.get('variants_to_create', 5)
        print(f"INFO: Wybieranie {num_products_to_modify} produktów do modyfikacji...")

        # Pobieramy ID wszystkich produktów
        products_response = self.api_client.get_all("products")
        if products_response.status_code != 200:
            print(f"ERROR: Nie udało się pobrać listy produktów. Status: {products_response.status_code}")
            return
        
        root = ET.fromstring(products_response.content)
        all_product_ids = [int(p.attrib['id']) for p in root.iter('product') if 'id' in p.attrib]

        # Losujemy produkty do modyfikacji
        if len(all_product_ids) < num_products_to_modify:
            product_candidates = all_product_ids
        else:
            product_candidates = random.sample(all_product_ids, num_products_to_modify)

        print(f"INFO: Wybrano produkty: {product_candidates}. Tworzenie kombinacji...")

        # Dla każdego produktu tworzymy kombinacje
        for product_id in product_candidates:
            for value_config in variants_config['attributes']:
                value_name = value_config['name']
                price_impact = value_config['price_impact']
                value_id = value_id_map[value_name]

                try:
                    root = ET.Element('prestashop')
                    combo = ET.SubElement(root, 'combination')
                    ET.SubElement(combo, 'id_product').text = str(product_id)
                    ET.SubElement(combo, 'minimal_quantity').text = '1'
                    ET.SubElement(combo, 'price').text = str(price_impact)

                    assoc = ET.SubElement(combo, 'associations')
                    povs = ET.SubElement(assoc, 'product_option_values')
                    pov = ET.SubElement(povs, 'product_option_value')
                    ET.SubElement(pov, 'id').text = str(value_id)

                    xml_bytes = ET.tostring(root, encoding='utf-8')
                    
                    # Wysyłamy kombinację do sklepu
                    response = self.api_client.post("combinations", xml_bytes)
                    if response.status_code == 201:
                        print(f"OK: Utworzono kombinację '{value_name}' dla produktu ID: {product_id}")
                    else:
                        print(f"ERROR: Nie utworzono kombinacji '{value_name}' dla ID {product_id}. Status: {response.status_code}, {response.text}")

                except Exception as e:
                    print(f"ERROR: Krytyczny błąd przy tworzeniu kombinacji dla produktu ID {product_id}: {e}")

if __name__ == "__main__":
    modifier = ProductModifier()
    modifier.set_heavy_products()
    modifier.create_promotions()
    modifier.create_variants()

    print("INFO: Modyfikacje produktów zakończone.")