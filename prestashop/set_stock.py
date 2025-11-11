import random
import tomllib
from pathlib import Path
import re
import xml.etree.ElementTree as ET
from concurrent.futures import ThreadPoolExecutor
from prestashop_base import APIClient, CONFIG_FILE

class StockUpdater:
    def __init__(self, config_file: str = CONFIG_FILE):
        self.config = self._load_config(config_file)
        self.api_client = APIClient(
            self.config['prestashop']['api_url'],
            self.config['prestashop']['api_key'],
            self.config['prestashop'].get('verify_ssl', True)
        )
        self.max_workers = self.config['import'].get('MAX_WORKERS', 5)
    
    """Wczytuje config TOML"""
    @staticmethod
    def _load_config(config_file: str) -> dict:
        config_path = Path(__file__).parent / config_file
        with open(config_path, 'rb') as f:
            return tomllib.load(f)
    
    """Ustawia stock dla wszystkich produktów w sklepie"""
    def update_all_products_stock(self) -> None:
        print("INFO: Aktualizacja stanów magazynowych...")
        
        # Pobieramy wszystkie produkty
        products = self._get_all_products()
        if not products:
            print("ERROR: Brak produktów do aktualizacji")
            return
        
        print(f"INFO: Znaleziono {len(products)} produktów")
        
        # Aktualizujemy stan magazynowy dla każdego produktu
        success_results = []
        with ThreadPoolExecutor(max_workers=self.max_workers) as executor:
            futures = [executor.submit(self._update_single_product_stock, prod_id) for prod_id in products]
            for future in futures:
                result = future.result()
                success_results.append(result)

        success_count = sum(success_results)
        print(f"OK: Zaktualizowano stan magazynowy dla {success_count}/{len(products)} produktów")
    
    """Pobiera listę ID wszystkich produktów"""
    def _get_all_products(self) -> list:
        try:
            response = self.api_client.get_all("products")
            
            if response.status_code == 200:
                root = ET.fromstring(response.content)
                products = root.findall('.//product')
                return [int(p.get('id')) for p in products]
        except Exception as e:
            print(f"ERROR: Pobieranie produktów - {e}")
        return []
    
    """Ustawia losowy stan magazynowy dla jednego produktu"""
    def _update_single_product_stock(self, prod_id: int) -> bool:
        try:
            # Pobieramy stock_available_id
            stock_id = self._get_stock_available_id(prod_id)
            if not stock_id:
                print(f"SKIP: Brak stock_available dla produktu {prod_id}")
                return False
            
            # Pobieramy pełny XML
            response = self.api_client.session.get(
                f"{self.api_client.api_url}/stock_availables/{stock_id}",
                headers={'Content-Type': 'application/xml'},
                auth=(self.api_client.api_key, ''),
                timeout=30,
                verify=self.api_client.verify_ssl
            )
            
            if response.status_code != 200:
                print(f"ERROR: GET stock XML prod {prod_id} - {response.status_code}")
                return False
            
            # Losujemy nową ilość
            quantity = self._get_random_quantity()
            
            # Zmieniamy ilość w XML
            xml_content = response.text
            updated_xml = re.sub(
                r'<quantity><!\[CDATA\[(\d+)\]\]></quantity>',
                f'<quantity><![CDATA[{quantity}]]></quantity>',
                xml_content
            )
            
            # Wysyłamy zmieniony XML
            put_response = self.api_client.session.put(
                f"{self.api_client.api_url}/stock_availables/{stock_id}",
                data=updated_xml.encode('utf-8'),
                headers={'Content-Type': 'application/xml'},
                auth=(self.api_client.api_key, ''),
                timeout=30,
                verify=self.api_client.verify_ssl
            )
            
            if put_response.status_code in (200, 201):
                print(f"OK: Produkt {prod_id} → stock={quantity}")
                return True
            else:
                print(f"ERROR: PUT stock prod {prod_id} - {put_response.status_code}")
                return False
                
        except Exception as e:
            print(f"ERROR: Update stock prod {prod_id} - {e}")
            return False
    
    """Pobiera stock_available_id dla produktu"""
    def _get_stock_available_id(self, prod_id: int) -> int:
        try:
            # URL z ręcznym encodowaniem nawiasów
            url = f"{self.api_client.api_url}/stock_availables?filter%5Bid_product%5D={prod_id}"
            
            response = self.api_client.session.get(
                url,
                headers={'Content-Type': 'application/xml'},
                auth=(self.api_client.api_key, ''),
                timeout=30,
                verify=self.api_client.verify_ssl
            )
            
            if response.status_code == 200:
                root = ET.fromstring(response.content)
                stock_elem = root.find('.//stock_available')
                if stock_elem is not None:
                    stock_id = stock_elem.get('id')
                    if stock_id:
                        return int(stock_id)
        except Exception as e:
            print(f"ERROR: Pobieranie stock_id prod {prod_id} - {e}")
        return None

    """Losuje ilość z zakresu z configu"""
    def _get_random_quantity(self) -> int:
        min_qty, max_qty = self.config['import']['product_quantity_range']
        return random.randint(min_qty, max_qty)


if __name__ == "__main__":
    updater = StockUpdater()
    updater.update_all_products_stock()