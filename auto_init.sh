#!/bin/bash

# Uruchamiamy zmianę nazwy w tle
(
    echo "Czekam 30s na start plików..."
    sleep 30
    
    # Jeśli istnieje folder 'admin', zmieniamy go na 'admin-numisfera'
    if [ -d "/var/www/html/admin" ]; then
        echo "Zmieniam nazwę folderu admin..."
        mv /var/www/html/admin /var/www/html/admin-numisfera
        # Nadajemy uprawnienia na wszelki wypadek
        chown -R www-data:www-data /var/www/html/admin-numisfera
    fi
) &

# Startujemy PrestaShop
exec /tmp/docker_run.sh
