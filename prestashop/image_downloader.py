import requests
import os
from pathlib import Path

class ImageDownloader:
    def __init__(self, output_dir: str = "product_images"):
        self.output_dir = Path(output_dir)
        self.output_dir.mkdir(exist_ok=True)
    
    """Pobiera obraz i zwraca ścieżkę lokalną"""
    def download_image(self, url: str, product_id: str, index: int) -> str:
        try:
            response = requests.get(url, timeout=10)
            if response.status_code == 200:
                # Nazwa pliku: product_id_index.jpg
                filename = f"{product_id}_{index}.jpg"
                filepath = self.output_dir / filename
                
                with open(filepath, 'wb') as f:
                    f.write(response.content)
                
                return str(filepath)
        except Exception as e:
            print(f"ERROR: Pobieranie obrazu {url} - {e}")
        return None
    
    """Pobiera wszystkie obrazy produktu"""
    def download_product_images(self, product_id: str, image_urls: list) -> list:
        local_paths = []
        for idx, url in enumerate(image_urls):
            path = self.download_image(url, product_id, idx)
            if path:
                local_paths.append(path)
        return local_paths
