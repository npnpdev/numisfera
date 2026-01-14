#!/bin/bash

# --- CZĘŚĆ NAPRAWCZA W TLE ---
(
    echo "[AUTO-INIT] Czekam 60s aż czysta Presta wstanie..."
    sleep 90

    SRC="/usr/src/prestashop_patch"
    DEST="/var/www/html"

    # Wykrywanie folderu
    if [ -d "$SRC/patch_final" ]; then
        SRC_FINAL="$SRC/patch_final"
    else
        SRC_FINAL="$SRC"
    fi

    echo "[AUTO-INIT] --- FAZA 1: PLIKI ---"

    # 1. Usuwamy parameters.php z patcha (bezpieczeństwo - config bierzemy z serwera)
    if [ -f "$SRC_FINAL/app/config/parameters.php" ]; then
        echo "Usuwam parameters.php z patcha..."
        rm "$SRC_FINAL/app/config/parameters.php"
    fi

    # 2. Kopiujemy pliki
    echo "Kopiuję pliki szablonu i modułów..."
    cp -av "$SRC_FINAL/." "$DEST/" 2>&1

    # 3. Twoje czyszczenie
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


    echo "[AUTO-INIT] --- FAZA 2: BAZA DANYCH ---"

    # Sprawdzamy, czy w patchu jest plik z bazą
    if [ -f "$SRC_FINAL/final_dump.sql" ]; then
        
        # Sprawdzamy czy baza jest zainicjalizowana
        PRODUCT_COUNT=$(mysql -h "$DB_SERVER" -u "$DB_USER" -p"$DB_PASSWD" "$DB_NAME" -N -e "SELECT count(*) FROM ps_product" 2>/dev/null)
        
        echo "Liczba produktów w bazie: $PRODUCT_COUNT"

        if [ "$PRODUCT_COUNT" -lt 30 ]; then
            echo "Wykryto bazę DEMO. Importuję Twój zrzut final_dump.sql..."
            
            # To nadpisze produkty demo Twoimi produktami
            mysql -h "$DB_SERVER" -u "$DB_USER" -p"$DB_PASSWD" "$DB_NAME" < "$SRC_FINAL/final_dump.sql"
            
            echo "Import bazy zakończony."
        else
            echo "Baza jest już wypełniona ($PRODUCT_COUNT produktów). Pomijam import."
        fi
    else
        echo "Brak pliku final_dump.sql - pomijam bazę."
    fi


    echo "[AUTO-INIT] --- FAZA 3: ZDJĘCIA ---"

    # Jeśli podmontowaliśmy backup zdjęć pod /img_backup (w docker-compose), 
    # to kopiujemy je do właściwego folderu. To naprawia problem instalatora.
    if [ -d "/var/www/html/img_backup" ]; then
        echo "Przywracam zdjęcia z backupu..."
        cp -rf /var/www/html/img_backup/* /var/www/html/img/
        echo "Zdjęcia przywrócone."
    fi


    echo "[AUTO-INIT] --- FINALIZACJA ---"

    # 4. Uprawnienia (KRYTYCZNE)
    echo "Naprawiam uprawnienia..."
    chown -R www-data:www-data "$DEST/img/" "$DEST/themes/" "$DEST/modules/" "$DEST/override/" "$DEST/translations/" "$DEST/var/"

    # 5. Zmiana nazwy admina
    if [ -d "$DEST/admin" ]; then
        mv "$DEST/admin" "$DEST/admin-numisfera" 2>/dev/null
    fi

    # 6. Czyszczenie cache
    rm -rf "$DEST/var/cache/*"

    echo "AUTO-INIT ZAKOŃCZONY SUKCESEM."
) &

# Start Presty
exec /tmp/docker_run.sh
