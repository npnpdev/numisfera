FROM prestashop/prestashop:1.7.8-apache

# Kopiujemy patch (żeby był dostępny)
COPY ./patch_final /usr/src/prestashop_patch

# Kopiujemy nasz skrypt naprawczy
COPY auto_init.sh /auto_init.sh

# Uprawnienia
RUN chown -R www-data:www-data /var/www/html && \
    chmod +x /auto_init.sh

# Uruchamiamy przez nasz skrypt
ENTRYPOINT ["/auto_init.sh"]
