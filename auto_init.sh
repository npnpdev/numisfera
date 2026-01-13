#!/bin/bash

# 1. Czekamy na bazę
sleep 5

# 2. Autoinicjalizacja bazy
echo "Inicjalizacja bazy danych..."
php /init_db.php

# --- CZĘŚĆ NAPRAWCZA W TLE ---
(
    # Czekamy 60 sekund aż skrypt Presty (docker_run.sh) skończy rozpakowywanie i startowanie
    sleep 180
    echo "Rozpoczynam patchowanie plików..."

    # Ścieżka gdzie w Dockerfile wrzuciłeś swoją czystą paczkę
    SRC="/usr/src/prestashop_patch"
    DEST="/var/www/html"

    # A. KOPIOWANIE PATCHA (Modyfikowane pliki)
    # Używamy -r, aby skopiować całą strukturę (themes i modules) na raz
    if [ -d "$SRC" ]; then
        cp -rv $SRC/* $DEST/
        echo "Kopiowanie plików patcha zakończone."
    else
        echo "BŁĄD: Folder $SRC nie istnieje!"
    fi

    # B. USUWANIE (Lista plików do wywalenia - "Syf")
    # Usunąłem z tej listy plik .png slidera, bo zaznaczyłeś, że jest potrzebny
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
    # Ważne: Nadajemy uprawnienia też dla themes (bo tam jest theme.css)
    chown -R www-data:www-data $DEST/modules/
    chown -R www-data:www-data $DEST/themes/
    
    # Czyszczenie cache, żeby Presta zobaczyła nowe pliki TPL i CSS
    rm -rf $DEST/var/cache/prod/*
    
    echo "Patchowanie zakończone pomyślnie. Sklep jest gotowy."
) &

# 4. Start oryginalnego skryptu Presty
exec /tmp/docker_run.sh
