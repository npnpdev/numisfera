Najważniejsze informacje z dotychczasowych prac:

AKTUALNA WERSJA NA BRANCHU: features/prestashop_base

- W folderze prestashop_base znajduje się cały silnik PRESTASHOP (.gitignore jest ustawiony, żeby ignorować pliki wskazane w instrukcji, cache itd.) - TUTAJ ZNAJDUJE SIE CAŁY PRESTASHOP RAZEM Z DOCKEROWYM OBRAZEM. Jest to wymagane, 
żeby zmiany, które będą wprowadzane w sklepie były widoczne u innych! Jest to też wersja stabilna bez błędów, zachowująca swój stan.

W jaki sposób odpalamy repozytorium?

1. git clone -b 'AKTUALNY BRANCH (ww.)' git@github.com:npnpdev/numisfera.git
2. cd prestashop_base 

ZANIM ODPALISZ DOCKERA I !PO ODPALENIU SKLEPU!:
git checkout -- modules/ - To polecenie jest niezbędne, żeby zmienione zdjęcia prawidłowo się wyświetlały, przy odpaleniu dockera prestashop próbuje siebie nadpisać. ODPALAJ TO
Z FOLDERU POZIOMU prestashop_base.

Na tym etapie odpalamy nasz obraz dockerowy:
docker compose up

Po 3-5 minutach sklep jest dostępny pod:
Sklep: http://localhost:8001/
Panel admina: http://localhost:8001/admin-dev

Po odpaleniu pierwsze co należy sprawdzić:
PRZEDE WSZYSTKIM CZY WIDAĆ ZMIANY NA STRONIE GŁÓWNEJ, CZYLI CZY NIE ŁADUJE SIĘ SKLEP 'DOMYŚLNY'!!! - JEŻELI TAK TO DUŻY BŁAD, powinno być zmienione logo i baner przynajmniej.
- Czy w panelu admina -> zaawansowane -> API jest wygenerowany już klucz i API jest aktywne - jeżeli tak nie ma potrzeby zmieniania configu (pliki config.tml z folderów prestashop i scraper)
- Czy kategorie są zaimportowane?
- Czy produkty są zaimportowane?
- Czy na stronach (aktualnie tylko na stronie głównej) nie pojawiają się błędy, brakujące zdjęcia

Jeżeli wszystko git to super. 

W jaki sposób działają skrypty:
scraper.py -> Generuje w tej kolejności:
- categories (wszystkie kategorie pobrane ze sklepu bazowego)
- products.json (plik pośredni do pobrania produktów! NIE WYKORZYSTUJEMY GO, potrzebny wyłacznie dla scrapera)
- products_detailed.json (plik z produktami)

Jak odpalisz scrapera i chcesz dodać coś od siebie:
1. Import categories - sklep powinien miec zaimportowane kategorie, ale możesz u siebie dla testów usunać wszystkie i zaimportować za pomocą tego skryptu
2. Import products - na ten moment importuje proste produkty (liczba produktów do importu określona w configu) #TODO, do skonczenia, żeby produkty miały opisy, zdjęcia etc.

NAJWAŻNIEJSZE: W JAKI SPOSÓB WYSYŁĄĆ WPROWADZAĆ ZMIANY!!!
1. Normalnie wprowadzasz zmiany u siebie lokalnie, wszystko co chcesz sobie edytujesz etc.
WYSYŁANIE NA GITHUBA (w tym momencie chcesz tylko wyslac zmiany, nie chcesz nic juz zmieniać)!:
1. Eksportujesz bazę danych! Docker później z tego pliku będzie odtwarzał bazę, robisz to z folderu prestashop_base, za pomocą:
docker-compose exec mysql mysqldump -uroot -pprestashop prestashop > prestashop_init.sql (sprawdź czy plik prestashop_init.sql w tym folderze sie zaaktualizował, np. po dacie)
1. Tworzysz dla siebie nowego brancha!!
2. git add .
3. Sprawdzasz git status! Sprawdź jakie pliki do wysłania dodał 'git add .', czy to są wszystkie pliki, których potrzebujesz, czy nie usunął czegoś ważnego etc.
4. git push 

UWAGA:
1. Po wysłaniu zmian, folder, w którym pracowałeś/aś ustaw sobie np. na 'nazwa_copy', czyli stwórz jego kopię zapasową!
2. Stwórz nowy folder, do którego zaciągniesz aktualnego brancha, na którym wprowadziłeś/aś zmiany i odpal sklep! Sprawdź czy wszystko wygląda tak jak powinno (czy jeżeli reszta zespólu ściągnie tę 
wersję to będą widoczne wszystkie zmiany, które wprowadziłeś/aś!)

Jeżeli tak, to super :)
Życzę miłej pracy.
