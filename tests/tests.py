import tomllib
from pathlib import Path
from prestashop_base import APIClient, CONFIG_FILE, DEFAULT_PARENT_CATEGORY_ID

"""Usuwa wszystkie kategorie ze sklepu"""
class CategoryDeleter:
    def __init__(self, config_file: str = CONFIG_FILE):
        self.config = self._load_config(config_file)
        self.api_client = APIClient(
            self.config['prestashop']['api_url'],
            self.config['prestashop']['api_key']
        )
    
    @staticmethod
    def _load_config(config_file: str) -> dict:
        config_path = Path(__file__).parent / config_file
        with open(config_path, 'rb') as f:
            return tomllib.load(f)
    
    """Pobiera wszystkie kategorie i je usuwa"""
    def delete_all_categories(self) -> None:
        print("ETAP 1: Pobieranie wszystkich kategorii...")
        try:
            response = self.api_client.get_all("categories")
            
            if response.status_code != 200:
                print(f"ERROR: Nie udało się pobrać kategorii - {response.status_code}")
                return
            
            # Parsowanie XML
            import xml.etree.ElementTree as ET
            root = ET.fromstring(response.content)
            categories = root.findall('category')
            
            if not categories:
                print("INFO: Brak kategorii do usunięcia")
                return
            
            print(f"INFO: Znaleziono {len(categories)} kategorii")
            
            # Filtruj tylko kategorie które NIE są root (ID != 2)
            to_delete = [cat for cat in categories if cat.find('id').text != str(DEFAULT_PARENT_CATEGORY_ID)]
            
            if not to_delete:
                print("INFO: Brak kategorii do usunięcia (oprócz root)")
                return
            
            print(f"\nETAP 2: Usuwanie {len(to_delete)} kategorii...")
            deleted_count = 0
            
            for cat in to_delete:
                cat_id = cat.find('id').text
                cat_name = cat.find('name').find('language').text if cat.find('name') else "Unknown"
                
                # DELETE request
                delete_response = self.api_client.session.delete(
                    f"{self.api_client.api_url}/categories/{cat_id}",
                    auth=(self.api_client.api_key, ''),
                    timeout=30
                )
                
                if delete_response.status_code in (200, 204):
                    print(f"✅ '{cat_name}' (ID: {cat_id}) - usunięta")
                    deleted_count += 1
                else:
                    print(f"❌ '{cat_name}' (ID: {cat_id}) - błąd {delete_response.status_code}")
            
            print(f"\n✅ Usunięto {deleted_count} kategorii")
            
        except Exception as e:
            print(f"ERROR: {e}")


if __name__ == "__main__":
    deleter = CategoryDeleter()
    deleter.delete_all_categories()
