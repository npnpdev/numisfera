#!/bin/bash

# 1. Czekamy na bazę
sleep 5

# 2. Autoinicjalizacja bazy
echo "Inicjalizacja bazy danych..."
php /init_db.php

# --- CZĘŚĆ NAPRAWCZA W TLE ---
(
    echo "Skrypt naprawczy: Czekam 180s na pełny start PrestaShop..."
    sleep 180

    SRC="/usr/src/prestashop_patch"
    DEST="/var/www/html"

    echo "----------------------------------------------------"
    echo "DIAGNOSTYKA START: $(date)"
    
    # KROK 1: Sprawdzenie CO DOKŁADNIE mamy w obrazie
    if [ -d "$SRC" ]; then
        echo "DOKŁADNA STRUKTURA PLIKÓW W PATCHU (ls -R):"
        ls -R "$SRC"
        echo "Rozmiar danych w źródle: $(du -sh "$SRC")"
    else
        echo "BŁĄD KRYTYCZNY: Folder $SRC NIE ISTNIEJE!"
        exit 1
    fi

    # KROK 2: Sprawdzenie stanu motywu classic przed patchem
    echo "Przykładowe pliki w docelowym motywie CLASSIC przed patchem:"
    ls -l "$DEST/themes/classic/assets/css/theme.css" 2>/dev/null

    # A. KOPIOWANIE PATCHA
    echo "Rozpoczynam patchowanie (cp -av)..."
    # Kopiujemy zawartość patcha do html
    cp -av "$SRC/." "$DEST/" 2>&1

    if [ $? -eq 0 ]; then
        echo "Kopiowanie plików zakończone."
    else
        echo "BŁĄD KOPIOWANIA! Kod: $?"
    fi

    # KROK 3: Weryfikacja po patchu
    echo "Stan motywu CLASSIC po patchu (sprawdzam czy daty się zmieniły):"
    ls -l "$DEST/themes/classic/assets/css/theme.css" 2>/dev/null

    # B. USUWANIE SYFU
    echo "Usuwanie zbędnych plików..."
    # (Twoja długa lista rm -f tutaj...)
    rm -f "$DEST/modules/blockreassurance/config_pl.xml"
    # ... reszta Twoich rm -f ...

    # C. UPRAWNIENIA I CACHE
    echo "Ustawiam uprawnienia i czyszczę cache..."
    chown -R www-data:www-data "$DEST/modules/"
    chown -R www-data:www-data "$DEST/themes/"
    rm -rf "$DEST/var/cache/*"

    echo "DIAGNOSTYKA KONIEC."
    echo "----------------------------------------------------"
) &

# 4. Start oryginalnego skryptu Presty
exec /tmp/docker_run.sh
