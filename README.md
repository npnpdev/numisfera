# Sklep Numizmatyczny - PrestaShop 1.7.8

Projekt sklepu internetowego z monetami i banknotami kolekcjonerskimi, zrealizowanego w ramach przedmiotu Biznes Elektroniczny.

## Opis projektu

Sklep internetowy oparty na platformie PrestaShop 1.7.8, zawierający ponad 1000 produktów (monety, banknoty, zestawy kolekcjonerskie) pobranych automatycznie ze sklepu https://numizmatyczny.com/. Projekt obejmuje pełną funkcjonalność e-commerce oraz testy automatyczne.

## Sklep źródłowy

**Numizmatyczny.com** - profesjonalny sklep z monetami złotymi, srebrnymi, banknotami kolekcjonerskimi i akcesoriami.

## Technologie

- PrestaShop 1.7.8
- Docker & Docker Compose
- Python 3.x (Anaconda - scraping, testy Selenium)
- MySQL
- HTTPS (SSL)

## Struktura projektu

```
.
├── prestashop/ # Kody źródłowe sklepu i docker-compose
├── scraper/ # Skrypty do scrapowania danych z numizmatyczny.com
│ └── results/ # Rezultaty scrapowania (JSON/CSV + zdjęcia monet)
├── tests/ # Testy automatyczne Selenium
├── deployment/ # Pliki konfiguracyjne i skrypty wdrożenia
└── README.md
```

## Kategorie produktów

- Monety Złote (kolekcjonerskie, inwestycyjne)
- Srebrne Monety (inwestycyjne, okolicznościowe)
- Banknoty Kolekcjonerskie
- Zestawy i Komplety
- Monety Obiegowe
- Akcesoria

## Jak uruchomić projekt

### Wymagania
- Docker Desktop
- Python 3.x
- Git

### Uruchomienie sklepu

1. Sklonuj repozytorium:
```
git clone https://github.com/npnpdev/numisfera
cd numisfera
```

2. Uruchom kontenery Docker:
```
cd prestashop
docker-compose up -d
```

3. Otwórz przeglądarkę: `https://localhost`

### Scraping danych ze sklepu numizmatyczny.com
```
cd scraper
python scraper.py
```

### Uruchomienie testów Selenium
```
cd tests
python selenium_tests.py
```

## Skład zespołu

- **Igor Tomkowicz** - Rola
- **Paweł Reguła** - Rola
- **Urszula Dramińska** - Rola
- **Aleksander Hlebowicz** - Rola

## Status projektu

🚧 W trakcie realizacji - Etap I

## Funkcjonalności sklepu

- Ponad 1000 produktów
- 4+ kategorie z podkategoriami
- System koszyka i zamówień
- Rejestracja i logowanie użytkowników
- Polskie metody płatności
- Dwóch przewoźników z różnymi opłatami
- Darmowa dostawa powyżej 2000 zł
- Powiadomienia e-mail
- Wyszukiwarka produktów
- Historia zamówień