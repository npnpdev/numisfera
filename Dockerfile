FROM prestashop/prestashop:8.1-apache

# 1. Instalujemy unzip (niezbędny do wypakowania patcha)
RUN apt-get update && apt-get install -y unzip

# 2. Kopiujemy pliki bazy i skrypty
COPY ./prestashop_base/prestashop_init.sql /tmp/init.sql
COPY init_db.php /init_db.php
COPY auto_init.sh /auto_init.sh

# 3. Kopiujemy główne pliki sklepu
# COPY ./prestashop_base/ /var/www/html/
COPY ./prestashop_base/admin-dev/ /var/www/html/admin-dev/

# 4. Kopiujemy folder
COPY ./patch_final /usr/src/prestashop_patch

# 5. Uprawnienia i czyszczenie
RUN rm -rf /var/www/html/install && \
    chown -R www-data:www-data /var/www/html && \
    chmod +x /auto_init.sh

ENV PS_INSTALL_AUTO=0
ENTRYPOINT ["/auto_init.sh"]
