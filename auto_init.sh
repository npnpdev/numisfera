#!/bin/bash

# 1. Czekamy na bazę
sleep 5

# 2. Autoinicjalizacja bazy
echo "Inicjalizacja bazy danych..."
php /init_db.php

# --- CZĘŚĆ NAPRAWCZA W TLE ---
(
    # Czekamy aż skrypt Presty skończy "mielić" pliki przy starcie
    sleep 60
    echo "Rozpoczynam patchowanie plików..."

    SRC="/patch_override/final_patch"
    DEST="/var/www/html/modules"

    # A. KOPIOWANIE (Mapowanie Twoich plików z final_patch na strukturę Presty)
    cp "$SRC/ps_contactinfo.php" "$DEST/ps_contactinfo/ps_contactinfo.php"
    cp "$SRC/config.xml" "$DEST/ps_contactinfo/config.xml"
    
    cp "$SRC/composer.json" "$DEST/ps_crossselling/composer.json"
    cp "$SRC/ps_crossselling.php" "$DEST/ps_crossselling/ps_crossselling.php"
    cp "$SRC/ps_crossselling.tpl" "$DEST/ps_crossselling/views/templates/hook/ps_crossselling.tpl"
    cp "$SRC/logo.png" "$DEST/ps_crossselling/logo.png"
    
    cp "$SRC/ps_mainmenu.php" "$DEST/ps_mainmenu/ps_mainmenu.php"
    
    cp "$SRC/ps_searchbar.php" "$DEST/ps_searchbar/ps_searchbar.php"
    cp "$SRC/ps_searchbar.js" "$DEST/ps_searchbar/ps_searchbar.js"
    cp "$SRC/ps_searchbar.css" "$DEST/ps_searchbar/ps_searchbar.css"
    
    cp "$SRC/ps_themecusto.php" "$DEST/ps_themecusto/ps_themecusto.php"
    cp "$SRC/AdminPsThemeCustoConfiguration.php" "$DEST/ps_themecusto/controllers/admin/AdminPsThemeCustoConfiguration.php"
    cp $SRC/phpstan-*.neon "$DEST/ps_themecusto/tests/phpstan/"
    
    cp "$SRC/statsbestcategories.php" "$DEST/statsbestcategories/statsbestcategories.php"
    cp "$SRC/upgrade-2.0.1.php" "$DEST/statsbestcategories/upgrade/upgrade-2.0.1.php"

    # B. USUWANIE (Twoja lista plików do wywalenia - DEST to /var/www/html/modules)
    rm -f "$DEST/blockreassurance/config_pl.xml"
    rm -f "$DEST/dashtrends/config_pl.xml"
    rm -f "$DEST/graphnvd3/config_pl.xml"
    rm -f "$DEST/ps_crossselling/Readme.md"
    rm -f "$DEST/ps_currencyselector/config_pl.xml"
    rm -f "$DEST/ps_emailsubscription/mails/pl/newsletter_conf.html"
    rm -f "$DEST/ps_emailsubscription/mails/pl/newsletter_conf.txt"
    rm -f "$DEST/ps_emailsubscription/mails/pl/newsletter_verif.html"
    rm -f "$DEST/ps_emailsubscription/mails/pl/newsletter_verif.txt"
    rm -f "$DEST/ps_emailsubscription/mails/pl/newsletter_voucher.html"
    rm -f "$DEST/ps_emailsubscription/mails/pl/newsletter_voucher.txt"
    rm -f "$DEST/ps_imageslider/images/0d897c61e0832e54c18631019d022cd4e8db6460_88c2e0f56fba7a0f066e481bcf856345.png"
    rm -f "$DEST/ps_imageslider/images/fileType"
    rm -f "$DEST/ps_languageselector/config_pl.xml"
    rm -f "$DEST/ps_mainmenu/upgrade/upgrade-2.3.5.php"
    rm -f "$DEST/ps_searchbar/config_pl.xml"
    rm -f "$DEST/ps_themecusto/config_pl.xml"
    rm -f "$DEST/statsbestcategories/README.md"
    rm -f "$DEST/statsbestcategories/composer.lock"
    rm -f "$DEST/statsbestcategories/config_pl.xml"
    rm -f "$DEST/statsbestcategories/tests/index.php"
    rm -f "$DEST/statsbestcategories/tests/phpstan.sh"
    rm -f "$DEST/statsbestcategories/tests/phpstan/index.php"
    rm -f "$DEST/statsbestcategories/tests/phpstan/phpstan-1.7.6.neon"
    rm -f "$DEST/statsbestcategories/tests/phpstan/phpstan-1.7.7.neon"
    rm -f "$DEST/statsbestcategories/tests/phpstan/phpstan-1.7.8.neon"
    rm -f "$DEST/statsbestcategories/tests/phpstan/phpstan-latest.neon"
    rm -f "$DEST/statsbestcategories/tests/phpstan/phpstan.neon"
    rm -rf "$DEST/statsbestcategories/vendor"
    rm -f "$DEST/statsbestproducts/config_pl.xml"
    rm -f "$DEST/statsbestvouchers/config_pl.xml"
    rm -f "$DEST/statscarrier/config_pl.xml"
    rm -f "$DEST/statscatalog/config_pl.xml"
    rm -f "$DEST/statsforecast/config_pl.xml"
    rm -f "$DEST/statsnewsletter/config_pl.xml"
    rm -f "$DEST/statspersonalinfos/config_pl.xml"
    rm -f "$DEST/statsregistrations/config_pl.xml"
    rm -f "$DEST/statssearch/config_pl.xml"

    # C. KOŃCZENIE
    chown -R www-data:www-data /var/www/html/modules/
    rm -rf /var/www/html/var/cache/prod/*
    echo "Patchowanie zakończone pomyślnie."
) & 

exec /tmp/docker_run.sh
