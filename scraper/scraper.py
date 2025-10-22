import requests
from bs4 import BeautifulSoup
import json
import os
import time
from urllib.parse import urljoin
import tomllib
from pathlib import Path

# Nazwa pliku konfiguracyjnego
CONFIG_FILE = 'config.toml'

class Scraper:
    def __init__(self, config_path=CONFIG_FILE):
        # Pobieramy katalog skryptu
        self.script_dir = Path(__file__).parent.resolve()
        
        # Wczytujemy konfigurację
        with open(self.script_dir / config_path, 'rb') as f:
            config = tomllib.load(f)
        
        self.base_url = config['scraper']['base_url']
        self.request_delay = config['scraper']['request_delay']
        self.timeout = config['scraper']['timeout']
        self.encoding = config['scraper']['encoding']
        
        # Tworzymy ścieżki względem folderu skryptu
        self.results_dir = self.script_dir / config['output']['results_dir']
        self.categories_file = config['output']['categories_file']
        self.products_file = config['output']['products_file']
        
        # Inicjalizujemy sesję HTTP
        self.session = requests.Session()
        self.session.headers.update({
            'User-Agent': config['scraper']['user_agent']
        })
        
        self.categories = []
        self.products = []
        
    def get_page(self, url):
        # Opóźnienie między requestami
        time.sleep(self.request_delay)
        
        try:
            response = self.session.get(url, timeout=self.timeout)
            response.raise_for_status()
            response.encoding = self.encoding
            return BeautifulSoup(response.text, 'lxml')
        except Exception as e:
            print(f"ERROR: Pobieranie {url}: {e}")
            return None
    
    def scrape_categories(self):
        # Pobieramy listę kategorii ze strony głównej
        print("INFO: Pobieranie kategorii...")
        page_content = self.get_page(self.base_url)
        
        if not page_content:
            return
        
        print("OK: Polaczono ze sklepem")
        
        # Szukamy głównego menu
        menu = page_content.find('ul', class_='menu-list')
        if not menu:
            print("ERROR: Nie znaleziono menu kategorii")
            return
        
        # Pobieramy TYLKO linki z li.parent (kategorie produktów)
        parent_items = menu.find_all('li', class_='parent')
        
        for parent_li in parent_items:
            # Znajdujemy wszystkie linki w tym li
            links = parent_li.find_all('a')
            
            for link in links:
                cat_name = link.get_text(strip=True)
                cat_url = link.get('href', '')
                
                if not cat_name or not cat_url or cat_url.startswith('#'):
                    continue
                
                # Konwertujemy na pełny URL
                full_url = urljoin(self.base_url, cat_url)
                
                # Sprawdzamy czy to nie duplikat
                if not any(cat['url'] == full_url for cat in self.categories):
                    self.categories.append({
                        'name': cat_name,
                        'url': full_url
                    })
        
        print(f"OK: Pobrano {len(self.categories)} kategorii")
        
        # Raportujemy pierwsze 5
        if self.categories:
            for i in range(min(5, len(self.categories))):
                print(f"INFO: [{i+1}] {self.categories[i]['name']}")

        
    def save_results(self):
        # Zapisujemy zebrane dane do plików JSON
        self.results_dir.mkdir(exist_ok=True)
        
        categories_path = self.results_dir / self.categories_file
        products_path = self.results_dir / self.products_file
        
        with open(categories_path, 'w', encoding=self.encoding) as f:
            json.dump(self.categories, f, ensure_ascii=False, indent=2)
        
        with open(products_path, 'w', encoding=self.encoding) as f:
            json.dump(self.products, f, ensure_ascii=False, indent=2)
        
        print(f"OK: Zapisano {len(self.categories)} kategorii")
        print(f"OK: Zapisano {len(self.products)} produktow")

if __name__ == "__main__":
    print("[Scraper]\n")
    scraper = Scraper()
    scraper.scrape_categories()
    scraper.save_results()
    print("\n[Zakonczono]")