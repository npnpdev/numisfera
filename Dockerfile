FROM prestashop/prestashop:8.1-apache

# Kopiujemy pliki Twojego automatu bazy danych
COPY ./prestashop_base/prestashop_init.sql /tmp/init.sql
COPY init_db.php /init_db.php
COPY auto_init.sh /auto_init.sh

# Kopiujemy Twoje pliki sklepu (z modułami i obrazkami)
COPY ./prestashop/ /var/www/html/

# Usuwamy instalator, nadajemy uprawnienia i robimy skrypt wykonywalnym
RUN rm -rf /var/www/html/install && \
    chown -R www-data:www-data /var/www/html && \
    chmod +x /auto_init.sh

# Wyłączamy instalator Presty
ENV PS_INSTALL_AUTO=0

# To jest kluczowe: mówimy kontenerowi, żeby startował od naszego skryptu
ENTRYPOINT ["/auto_init.sh"]
