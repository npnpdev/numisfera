import tomllib
from pathlib import Path
from requests.auth import HTTPBasicAuth
import requests
import time

CONFIG_FILE = 'config.toml'
DEFAULT_PARENT_CATEGORY_ID = 2

def load_config(config_file: str) -> dict:
    config_path = Path(__file__).parent / config_file
    with open(config_path, 'rb') as f:
        return tomllib.load(f)

config = load_config(CONFIG_FILE)
api_url = config['prestashop']['api_url']
api_key = config['prestashop']['api_key']

print("Pobieranie kategorii...")
response = requests.get(
    f"{api_url}/categories",
    headers={'Content-Type': 'application/xml'},
    auth=HTTPBasicAuth(api_key, ''),
    timeout=30
)

if response.status_code != 200:
    print(f"ERROR: {response.status_code}")
    exit(1)

import xml.etree.ElementTree as ET
root = ET.fromstring(response.content)

# KATEGORIE SĄ W <categories> - NIE W ROOT!
categories_elem = root.find('categories')
if categories_elem is None:
    print("ERROR: Nie znaleziono <categories>")
    exit(1)

# ID JEST W ATRYBUCIE
category_ids = []
for cat in categories_elem.findall('category'):
    cat_id = cat.get('id')  # ← ID W ATRYBUCIE!
    if cat_id and int(cat_id) != DEFAULT_PARENT_CATEGORY_ID:
        category_ids.append(cat_id)

print(f"Znaleziono {len(category_ids)} kategorii do usunięcia\n")

deleted = 0
for cat_id in category_ids:
    del_response = requests.delete(
        f"{api_url}/categories/{cat_id}",
        auth=HTTPBasicAuth(api_key, ''),
        timeout=30
    )
    
    if del_response.status_code in (200, 204):
        print(f"✅ Usunięto kategorię ID: {cat_id}")
        deleted += 1
    else:
        print(f"❌ Błąd przy ID {cat_id}: {del_response.status_code}")

print(f"\n✅ USUNIĘTO {deleted} kategorii")
