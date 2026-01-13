#!/bin/bash

# 1. Czekamy na bazę
sleep 5

# 2. Autoinicjalizacja bazy
echo "Inicjalizacja bazy danych..."
php /init_db.php

# --- CZĘŚĆ NAPRAWCZA W TLE ---
(
    echo "Skrypt naprawczy: Czekam 180s na pełny start PrestaShop..."
    sleep 60

    SRC="/usr/src/prestashop_patch"
    DEST="/var/www/html"

    # Automatyczne wykrycie czy w ZIPie był folder 'patch_final'
    if [ -d "$SRC/patch_final" ]; then
        SRC_FINAL="$SRC/patch_final"
    else
        SRC_FINAL="$SRC"
    fi

    echo "----------------------------------------------------"
    echo "DIAGNOSTYKA START: $(date)"
    echo "ADRES PANELU ADMINA: http://localhost:19410/$(ls -d $DEST/admin*/ | xargs -n 1 basename)/"
    echo "Rozmiar źródła: $(du -sh "$SRC_FINAL")"
    echo "Zawartość źródła: $(ls -F "$SRC_FINAL")"

    echo "--- !!! ZASOBY DLA ADMINA !!! ---"
    # Czekamy aż plik powstanie
    while [ ! -f "$DEST/app/config/parameters.php" ]; do sleep 2; done
    # Wypisujemy klucz do logów klastra
    grep "cookie_key" "$DEST/app/config/parameters.php"
    echo "----------------------------------"

    # Naprawa błędu ParameterNotFoundException (brakujący mailer)
    php -r '$path = "/var/www/html/app/config/parameters.php"; $conf = include $path; $missing = ["mailer_transport" => "smtp", "mailer_host" => "127.0.0.1", "mailer_user" => null, "mailer_password" => null, "mailer_port" => null, "secret" => "secret_key_rsww"]; $conf["parameters"] = array_merge($missing, $conf["parameters"]); file_put_contents($path, "<?php\nreturn " . var_export($conf, true) . ";");'

    # A. KOPIOWANIE PATCHA (wszystkie 5 folderów)
    echo "Rozpoczynam patchowanie wszystkich katalogów (cp -av)..."
    cp -av "$SRC_FINAL/." "$DEST/" 2>&1

    # B. USUWANIE SYFU (Twoja lista)
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

    # C. UPRAWNIENIA I CACHE (kluczowe dla img/ i override/)
    echo "Ustawiam uprawnienia dla www-data w kluczowych folderach..."
    chown -R www-data:www-data "$DEST/img/" "$DEST/themes/" "$DEST/modules/" "$DEST/override/" "$DEST/translations/"

    chown -R www-data:www-data "$DEST/img/" "$DEST/themes/" "$DEST/modules/" "$DEST/override/" "$DEST/translations/" "$DEST/var/" "$DEST/app/config/"

    chown -R www-data:www-data "$DEST/app/config/" "$DEST/img/" "$DEST/themes/" "$DEST/modules/" "$DEST/override/" "$DEST/translations/"

    mv "$DEST/admin" "$DEST/admin-numisfera" 2>/dev/null
    
    echo "Czyszczenie cache..."
    rm -rf "$DEST/var/cache/*"

    echo "DIAGNOSTYKA KONIEC. Sklep powinien być kompletny."
    echo "----------------------------------------------------"
) &

# 4. Start oryginalnego skryptu Presty
exec /tmp/docker_run.sh
