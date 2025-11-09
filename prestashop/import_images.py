import requests
from pathlib import Path
import tomllib

class ImageDownloader:
    def __init__(self, images_dir: Path):
        self.images_dir = Path(images_dir)
        self.images_dir.mkdir(parents=True, exist_ok=True)

    """Pobiera wszystkie obrazy dla produktu."""
    def download_product_images(self, prod_id: int, image_urls: list) -> list:
        product_folder = self.images_dir / str(prod_id)
        product_folder.mkdir(exist_ok=True)
        
        local_paths = []
        for idx, url in enumerate(image_urls):
            local_path = self._download_single_image(url, product_folder, idx)
            if local_path:
                local_paths.append(local_path)
        
        return local_paths
    
    """Pobiera pojedynczy obraz."""
    def _download_single_image(self, url: str, folder: Path, index: int) -> str:
        try:
            response = requests.get(url, timeout=10)
            if response.status_code == 200:
                filename = f"{index}.jpg"
                filepath = folder / filename
                
                with open(filepath, 'wb') as f:
                    f.write(response.content)
                
                # print(f"DEBUG: Pobrano {filename}")
                return str(filepath)
        except Exception as e:
            print(f"ERROR: Pobieranie {url} - {e}")
        
        return None


class ImageUploader:
    def __init__(self, config: dict):
        self.config = config
    
    def upload_product_images(self, prod_id: int, local_paths: list) -> None:
        """Wysyła obrazy do PrestaShopa."""
        api_url = self.config['prestashop']['api_url']
        api_key = self.config['prestashop']['api_key']
        
        for idx, local_path in enumerate(local_paths):
            try:
                with open(local_path, 'rb') as f:
                    files = {'image': f}
                    response = requests.post(
                        f"{api_url}/images/products/{prod_id}",
                        auth=(api_key, ''),
                        files=files
                    )
                
                if response.status_code in (200, 201):
                    print(f"OK: Wysłano {idx}.jpg dla produktu {prod_id}")
                else:
                    print(f"ERROR: {response.status_code} - {response.text[:200]}")
            except Exception as e:
                print(f"ERROR: Wysyłanie {local_path} - {e}")

"""Główna funkcja - pobiera i importuje obrazy produktu"""
def process_product_images(prod_id: int, image_urls: list, config: dict) -> None:
    results_dir = Path(__file__).parent.parent / config['paths']['results_dir']
    images_dir = results_dir / "images"
    
    # Pobieramy obrazy lokalnie
    downloader = ImageDownloader(images_dir)
    local_paths = downloader.download_product_images(prod_id, image_urls)
    
    if not local_paths:
        print(f"ERROR: Brak pobranych obrazów dla produktu {prod_id}")
        return
    
    # Wysyłamy obrazy do PrestaShopa
    uploader = ImageUploader(config)
    uploader.upload_product_images(prod_id, local_paths)