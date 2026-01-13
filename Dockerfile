FROM prestashop/prestashop:8.1-apache

# 1. Instalujemy unzip (niezbędny do wypakowania patcha)
RUN apt-get update && apt-get install -y unzip

# 2. Kopiujemy pliki bazy i skrypty
COPY ./prestashop_base/prestashop_init.sql /tmp/init.sql
COPY init_db.php /init_db.php
COPY auto_init.sh /auto_init.sh

# 3. Kopiujemy główne pliki sklepu
COPY ./prestashop/ /var/www/html/

# 4. KLUCZOWY MOMENT: Kopiujemy ZIPa (który jest na branchu) i go rozpakowujemy
COPY ./sklep_patch.zip /tmp/patch.zip
RUN unzip /tmp/patch.zip -d /usr/src/ && \
    mv /usr/src/patch_final /usr/src/prestashop_patch && \
    rm /tmp/patch.zip

# 5. Uprawnienia i czyszczenie
RUN rm -rf /var/www/html/install && \
    chown -R www-data:www-data /var/www/html && \
    chmod +x /auto_init.sh

ENV PS_INSTALL_AUTO=0
ENTRYPOINT ["/auto_init.sh"]
