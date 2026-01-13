#!/bin/bash

# 1. Czekamy na bazę
sleep 5

# 2. Autoinicjalizacja bazy
echo "Inicjalizacja bazy danych..."
php /init_db.php

# --- CZĘŚĆ NAPRAWCZA W TLE ---
(
    # Czekamy 180 sekund (3 minuty) aż Presta skończy się rozpakowywać
    echo "Skrypt naprawczy: Czekam 180s na pełny start PrestaShop..."
    sleep 180

    SRC="/usr/src/prestashop_patch"
    DEST="/var/www/html"

    echo "----------------------------------------------------"
    echo "DIAGNOSTYKA START: $(date)"
    
    # KROK 1: Sprawdzenie folderu źródłowego
    if [ -d "$SRC" ]; then
        echo "OK: Folder źródłowy $SRC istnieje."
        echo "Zawartość $SRC (ls -la):"
        ls -la "$SRC"
        echo "Rozmiar danych w źródle (du -sh):"
        du -sh "$SRC"
    else
        echo "BŁĄD KRYTYCZNY: Folder $SRC NIE ISTNIEJE w kontenerze!"
        echo "Sprawdź czy w Dockerfile masz: COPY <folder> $SRC"
        exit 1
    fi

    # KROK 2: Sprawdzenie celu przed kopiowaniem
    echo "Stan folderu docelowego $DEST/themes przed patchem:"
    ls -la "$DEST/themes" 2>/dev/null | head -n 10

    # A. KOPIOWANIE PATCHA
    echo "Rozpoczynam patchowanie plików (cp -av)..."
    # Używamy /. aby skopiować ZAWARTOŚĆ folderu patcha do html
    # 2>&1 przekierowuje błędy kopiowania do logów Dockera
    cp -av "$SRC/." "$DEST/" 2>&1

    if [ $? -eq 0 ]; then
        echo "Kopiowanie plików zakończone sukcesem."
    else
        echo "WYSTĄPIŁ BŁĄD podczas kopiowania! Kod wyjścia: $?"
    fi

    # B. USUWANIE (Twoja lista plików "Syf")
    echo "Usuwanie zbędnych plików..."
    rm -f "$DEST/modules/blockreassurance/config_pl.xml"
    rm -f "$DEST/modules/dashtrends/config_pl.xml"
    rm -f "$DEST/modules/graphnvd3/config_pl.xml"
    rm -f "$DEST/modules/ps_crossselling/Readme.md"
    rm -f "$DEST/modules/ps_currencyselector/config_pl.xml"
    rm -f "$DEST/modules/ps_emailsubscription/mails/pl/newsletter_conf.html"
    rm -f "$DEST/modules/ps_emailsubscription/mails/pl/newsletter_conf.txt"
    rm -f "$DEST/modules/ps_emailsubscription/mails/pl/newsletter_verif.html"
    rm -f "$DEST/modules/ps_emailsubscription/mails/pl/newsletter_verif.txt"
    rm -f "$DEST/modules/ps_emailsubscription/mails/pl/newsletter_voucher.html"
    rm -f "$DEST/modules/ps_emailsubscription/mails/pl/newsletter_voucher.txt"
    rm -f "$DEST/modules/ps_imageslider/images/fileType"
    rm -f "$DEST/modules/ps_languageselector/config_pl.xml"
    rm -f "$DEST/modules/ps_mainmenu/upgrade/upgrade-2.3.5.php"
    rm -f "$DEST/modules/ps_searchbar/config_pl.xml"
    rm -f "$DEST/modules/ps_themecusto/config_pl.xml"
    rm -f "$DEST/modules/statsbestcategories/README.md"
    rm -f "$DEST/modules/statsbestcategories/composer.lock"
    rm -f "$DEST/modules/statsbestcategories/config_pl.xml"
    rm -f "$DEST/modules/statsbestcategories/tests/index.php"
    rm -f "$DEST/modules/statsbestcategories/tests/phpstan.sh"
    rm -f "$DEST/modules/statsbestcategories/tests/phpstan/index.php"
    rm -f "$DEST/modules/statsbestcategories/tests/phpstan/phpstan-1.7.6.neon"
    rm -f "$DEST/modules/statsbestcategories/tests/phpstan/phpstan-1.7.7.neon"
    rm -f "$DEST/modules/statsbestcategories/tests/phpstan/phpstan-1.7.8.neon"
    rm -f "$DEST/modules/statsbestcategories/tests/phpstan/phpstan-latest.neon"
    rm -f "$DEST/modules/statsbestcategories/tests/phpstan/phpstan.neon"
    rm -rf "$DEST/modules/statsbestcategories/vendor"
    rm -f "$DEST/modules/statsbestproducts/config_pl.xml"
    rm -f "$DEST/modules/statsbestvouchers/config_pl.xml"
    rm -f "$DEST/modules/statscarrier/config_pl.xml"
    rm -f "$DEST/modules/statscatalog/config_pl.xml"
    rm -f "$DEST/modules/statsforecast/config_pl.xml"
    rm -f "$DEST/modules/statsnewsletter/config_pl.xml"
    rm -f "$DEST/modules/statspersonalinfos/config_pl.xml"
    rm -f "$DEST/modules/statsregistrations/config_pl.xml"
    rm -f "$DEST/modules/statssearch/config_pl.xml"

    # C. KOŃCZENIE - Uprawnienia i Cache
    echo "Ustawiam uprawnienia dla www-data..."
    chown -R www-data:www-data "$DEST/modules/"
    chown -R www-data:www-data "$DEST/themes/"

    echo "Czyszczenie cache Smarty i Symfony..."
    rm -rf "$DEST/var/cache/prod/*"
    rm -rf "$DEST/var/cache/dev/*"

    echo "DIAGNOSTYKA KONIEC. Patchowanie zakończone."
    echo "----------------------------------------------------"
) &

# 4. Start oryginalnego skryptu Presty
exec /tmp/docker_run.sh
