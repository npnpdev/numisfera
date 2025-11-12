from import_attributes import AttributeImporter
from import_categories import PrestashopImporter
from import_products import ProductImporter
from set_stock import StockUpdater
from modify_products import ProductModifier
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
    print("\n[5. Modyfikowanie produktów]")
    ProductModifier().set_heavy_products()
    ProductModifier().create_promotions()
    ProductModifier().create_variants()
    end = time.time()
    elapsed = end - start
    print(f"\n|KONIEC IMPORTU DO PRESTASHOP| (Czas: {elapsed:.1f} sekund)")

if __name__ == "__main__":
    main()
