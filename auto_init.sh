#!/bin/bash

# 1. Czekamy na bazę danych i sieć
sleep 5

# 2. Autoinicjalizacja bazy danych (Wymóg projektu)
echo "Inicjalizacja bazy danych..."
php /init_db.php

# --- CZĘŚĆ NAPRAWCZA W TLE ---
(
    # Czekamy, aż oryginalny skrypt Presty (docker_run.sh) skończy rozpakowywać pliki
    # 60 sekund to bezpieczny czas
    sleep 60
    
    echo "Rozpoczynam patchowanie plików..."

    # A. KOPIOWANIE PLIKÓW (Twoja lista 40 plików)
    # Używamy -p, aby zachować strukturę, jeśli katalogi by nie istniały (choć powinny)
    cp /patch_override/modules/ps_contactinfo/config.xml /var/www/html/modules/ps_contactinfo/config.xml
    cp /patch_override/modules/ps_contactinfo/ps_contactinfo.php /var/www/html/modules/ps_contactinfo/ps_contactinfo.php
    cp /patch_override/modules/ps_crossselling/composer.json /var/www/html/modules/ps_crossselling/composer.json
    cp /patch_override/modules/ps_crossselling/config.xml /var/www/html/modules/ps_crossselling/config.xml
    cp /patch_override/modules/ps_crossselling/index.php /var/www/html/modules/ps_crossselling/index.php
    cp /patch_override/modules/ps_crossselling/logo.png /var/www/html/modules/ps_crossselling/logo.png
    cp /patch_override/modules/ps_crossselling/ps_crossselling.php /var/www/html/modules/ps_crossselling/ps_crossselling.php
    cp /patch_override/modules/ps_crossselling/views/index.php /var/www/html/modules/ps_crossselling/views/index.php
    cp /patch_override/modules/ps_crossselling/views/templates/hook/index.php /var/www/html/modules/ps_crossselling/views/templates/hook/index.php
    cp /patch_override/modules/ps_crossselling/views/templates/hook/ps_crossselling.tpl /var/www/html/modules/ps_crossselling/views/templates/hook/ps_crossselling.tpl
    cp /patch_override/modules/ps_crossselling/views/templates/index.php /var/www/html/modules/ps_crossselling/views/templates/index.php
    cp /patch_override/modules/ps_mainmenu/README.md /var/www/html/modules/ps_mainmenu/README.md
    cp /patch_override/modules/ps_mainmenu/composer.json /var/www/html/modules/ps_mainmenu/composer.json
    cp /patch_override/modules/ps_mainmenu/composer.lock /var/www/html/modules/ps_mainmenu/composer.lock
    cp /patch_override/modules/ps_mainmenu/config.xml /var/www/html/modules/ps_mainmenu/config.xml
    cp /patch_override/modules/ps_mainmenu/ps_mainmenu.php /var/www/html/modules/ps_mainmenu/ps_mainmenu.php
    cp /patch_override/modules/ps_searchbar/README.md /var/www/html/modules/ps_searchbar/README.md
    cp /patch_override/modules/ps_searchbar/composer.json /var/www/html/modules/ps_searchbar/composer.json
    cp /patch_override/modules/ps_searchbar/composer.lock /var/www/html/modules/ps_searchbar/composer.lock
    cp /patch_override/modules/ps_searchbar/config.xml /var/www/html/modules/ps_searchbar/config.xml
    cp /patch_override/modules/ps_searchbar/ps_searchbar.css /var/www/html/modules/ps_searchbar/ps_searchbar.css
    cp /patch_override/modules/ps_searchbar/ps_searchbar.js /var/www/html/modules/ps_searchbar/ps_searchbar.js
    cp /patch_override/modules/ps_searchbar/ps_searchbar.php /var/www/html/modules/ps_searchbar/ps_searchbar.php
    cp /patch_override/modules/ps_themecusto/config.xml /var/www/html/modules/ps_themecusto/config.xml
    cp /patch_override/modules/ps_themecusto/controllers/admin/AdminPsThemeCustoConfiguration.php /var/www/html/modules/ps_themecusto/controllers/admin/AdminPsThemeCustoConfiguration.php
    cp /patch_override/modules/ps_themecusto/ps_themecusto.php /var/www/html/modules/ps_themecusto/ps_themecusto.php
    cp /patch_override/modules/ps_themecusto/tests/phpstan/phpstan-1.7.1.2.neon /var/www/html/modules/ps_themecusto/tests/phpstan/phpstan-1.7.1.2.neon
    cp /patch_override/modules/ps_themecusto/tests/phpstan/phpstan-1.7.2.5.neon /var/www/html/modules/ps_themecusto/tests/phpstan/phpstan-1.7.2.5.neon
    cp /patch_override/modules/ps_themecusto/tests/phpstan/phpstan-1.7.3.4.neon /var/www/html/modules/ps_themecusto/tests/phpstan/phpstan-1.7.3.4.neon
    cp /patch_override/modules/ps_themecusto/tests/phpstan/phpstan-1.7.4.4.neon /var/www/html/modules/ps_themecusto/tests/phpstan/phpstan-1.7.4.4.neon
    cp /patch_override/modules/ps_themecusto/tests/phpstan/phpstan-1.7.5.1.neon /var/www/html/modules/ps_themecusto/tests/phpstan/phpstan-1.7.5.1.neon
    cp /patch_override/modules/ps_themecusto/tests/phpstan/phpstan-1.7.6.neon /var/www/html/modules/ps_themecusto/tests/phpstan/phpstan-1.7.6.neon
    cp /patch_override/modules/ps_themecusto/tests/phpstan/phpstan-1.7.7.neon /var/www/html/modules/ps_themecusto/tests/phpstan/phpstan-1.7.7.neon
    cp /patch_override/modules/statsbestcategories/composer.json /var/www/html/modules/statsbestcategories/composer.json
    cp /patch_override/modules/statsbestcategories/config.xml /var/www/html/modules/statsbestcategories/config.xml
    cp /patch_override/modules/statsbestcategories/index.php /var/www/html/modules/statsbestcategories/index.php
    cp /patch_override/modules/statsbestcategories/statsbestcategories.php /var/www/html/modules/statsbestcategories/statsbestcategories.php
    cp /patch_override/modules/statsbestcategories/translations/index.php /var/www/html/modules/statsbestcategories/translations/index.php
    cp /patch_override/modules/statsbestcategories/upgrade/index.php /var/www/html/modules/statsbestcategories/upgrade/index.php
    cp /patch_override/modules/statsbestcategories/upgrade/upgrade-2.0.1.php /var/www/html/modules/statsbestcategories/upgrade/upgrade-2.0.1.php

    # B. USUWANIE PLIKÓW (Twoja lista 50 plików)
    rm -f /var/www/html/modules/blockreassurance/config_pl.xml
    rm -f /var/www/html/modules/dashtrends/config_pl.xml
    rm -f /var/www/html/modules/graphnvd3/config_pl.xml
    rm -f /var/www/html/modules/ps_crossselling/Readme.md
    rm -f /var/www/html/modules/ps_currencyselector/config_pl.xml
    rm -f /var/www/html/modules/ps_emailsubscription/mails/pl/newsletter_conf.html
    rm -f /var/www/html/modules/ps_emailsubscription/mails/pl/newsletter_conf.txt
    rm -f /var/www/html/modules/ps_emailsubscription/mails/pl/newsletter_verif.html
    rm -f /var/www/html/modules/ps_emailsubscription/mails/pl/newsletter_verif.txt
    rm -f /var/www/html/modules/ps_emailsubscription/mails/pl/newsletter_voucher.html
    rm -f /var/www/html/modules/ps_emailsubscription/mails/pl/newsletter_voucher.txt
    rm -f /var/www/html/modules/ps_imageslider/images/0d897c61e0832e54c18631019d022cd4e8db6460_88c2e0f56fba7a0f066e481bcf856345.png
    rm -f /var/www/html/modules/ps_imageslider/images/fileType
    rm -f /var/www/html/modules/ps_languageselector/config_pl.xml
    rm -f /var/www/html/modules/ps_mainmenu/upgrade/upgrade-2.3.5.php
    rm -f /var/www/html/modules/ps_searchbar/config_pl.xml
    rm -f /var/www/html/modules/ps_themecusto/config_pl.xml
    rm -f /var/www/html/modules/statsbestcategories/README.md
    rm -f /var/www/html/modules/statsbestcategories/composer.lock
    rm -f /var/www/html/modules/statsbestcategories/config_pl.xml
    rm -f /var/www/html/modules/statsbestcategories/tests/index.php
    rm -f /var/www/html/modules/statsbestcategories/tests/phpstan.sh
    rm -f /var/www/html/modules/statsbestcategories/tests/phpstan/index.php
    rm -f /var/www/html/modules/statsbestcategories/tests/phpstan/phpstan-1.7.6.neon
    rm -f /var/www/html/modules/statsbestcategories/tests/phpstan/phpstan-1.7.7.neon
    rm -f /var/www/html/modules/statsbestcategories/tests/phpstan/phpstan-1.7.8.neon
    rm -f /var/www/html/modules/statsbestcategories/tests/phpstan/phpstan-latest.neon
    rm -f /var/www/html/modules/statsbestcategories/tests/phpstan/phpstan.neon
    rm -f /var/www/html/modules/statsbestcategories/vendor/.htaccess
    rm -f /var/www/html/modules/statsbestcategories/vendor/autoload.php
    rm -f /var/www/html/modules/statsbestcategories/vendor/composer/ClassLoader.php
    rm -f /var/www/html/modules/statsbestcategories/vendor/composer/InstalledVersions.php
    rm -f /var/www/html/modules/statsbestcategories/vendor/composer/LICENSE
    rm -f /var/www/html/modules/statsbestcategories/vendor/composer/autoload_classmap.php
    rm -f /var/www/html/modules/statsbestcategories/vendor/composer/autoload_namespaces.php
    rm -f /var/www/html/modules/statsbestcategories/vendor/composer/autoload_psr4.php
    rm -f /var/www/html/modules/statsbestcategories/vendor/composer/autoload_real.php
    rm -f /var/www/html/modules/statsbestcategories/vendor/composer/autoload_static.php
    rm -f /var/www/html/modules/statsbestcategories/vendor/composer/installed.json
    rm -f /var/www/html/modules/statsbestcategories/vendor/composer/installed.php
    rm -f /var/www/html/modules/statsbestcategories/vendor/composer/platform_check.php
    rm -f /var/www/html/modules/statsbestproducts/config_pl.xml
    rm -f /var/www/html/modules/statsbestvouchers/config_pl.xml
    rm -f /var/www/html/modules/statscarrier/config_pl.xml
    rm -f /var/www/html/modules/statscatalog/config_pl.xml
    rm -f /var/www/html/modules/statsforecast/config_pl.xml
    rm -f /var/www/html/modules/statsnewsletter/config_pl.xml
    rm -f /var/www/html/modules/statspersonalinfos/config_pl.xml
    rm -f /var/www/html/modules/statsregistrations/config_pl.xml
    rm -f /var/www/html/modules/statssearch/config_pl.xml

    # C. NAPRAWA UPRAWNIEŃ (Bardzo ważne!)
    # Musimy oddać pliki użytkownikowi www-data
    chown -R www-data:www-data /var/www/html/modules/

    # D. CZYSZCZENIE CACHE
    echo "Czyszczenie cache..."
    rm -rf /var/www/html/var/cache/prod/*
    
    echo "Patchowanie zakończone pomyślnie."
) & 

# 3. Odpalamy sklep (ORYGINALNA KOMENDA)
# To musi zostać jako proces główny (foreground)
exec /tmp/docker_run.sh
