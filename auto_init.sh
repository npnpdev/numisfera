#!/bin/bash

# --- CZĘŚĆ NAPRAWCZA W TLE ---
(
    echo "Czekam 60s aż czysta Presta wstanie..."
    sleep 60

    SRC="/usr/src/prestashop_patch"
    DEST="/var/www/html"

    # Wykrywanie folderu
    if [ -d "$SRC/patch_final" ]; then
        SRC_FINAL="$SRC/patch_final"
    else
        SRC_FINAL="$SRC"
    fi

    echo "Rozpoczynam nakładanie patcha na działający sklep..."
    
    # 1. Usuwamy parameters.php z patcha (jeśli tam jest), żeby NIE popsuć bazy
    if [ -f "$SRC_FINAL/app/config/parameters.php" ]; then
        echo "Usuwam parameters.php z patcha (bezpieczeństwo)..."
        rm "$SRC_FINAL/app/config/parameters.php"
    fi

    # 2. Kopiujemy pliki
    cp -av "$SRC_FINAL/." "$DEST/" 2>&1

    # 3. Twoje czyszczenie (lista z poprzedniego pliku)
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
    rm -rf "$DEST/modules/statsbestcategories/vendor"

    # 4. Uprawnienia (KRYTYCZNE)
    echo "Naprawiam uprawnienia..."
    chown -R www-data:www-data "$DEST/img/" "$DEST/themes/" "$DEST/modules/" "$DEST/override/" "$DEST/translations/" "$DEST/var/"
    
    # 5. Zmiana nazwy admina (dla pewności, jeśli patch przywrócił folder 'admin')
    if [ -d "$DEST/admin" ]; then
        mv "$DEST/admin" "$DEST/admin-numisfera" 2>/dev/null
    fi

    # 6. Czyszczenie cache (żeby zobaczyć zmiany od razu)
    rm -rf "$DEST/var/cache/*"

    echo "Gotowe. Pliki podmienione."
) &

# Start Presty
exec /tmp/docker_run.sh
