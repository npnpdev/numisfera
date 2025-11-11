from import_attributes import AttributeImporter
from import_categories import PrestashopImporter
from import_products import ProductImporter
from set_stock import StockUpdater
import time

def main():
    start = time.time()
    print("| ROZPOCZYNAM IMPORT DO PRESTASHOP |\n")
    print("[1. Importowanie atrybutów]")
    AttributeImporter().import_attributes()
    print("\n[2. Importowanie kategorii]")
    PrestashopImporter().import_categories()
    print("\n[3. Importowanie produktów]")
    ProductImporter().import_products()
    print("\n[4. Ustawianie stanów magazynowych]")
    StockUpdater().update_all_products_stock()
    end = time.time()
    elapsed = end - start
    print(f"\n|KONIEC IMPORTU DO PRESTASHOP| (Czas: {elapsed:.1f} sekund)")

if __name__ == "__main__":
    main()
