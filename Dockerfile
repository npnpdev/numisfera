FROM prestashop/prestashop:1.7.8-apache

# 1. Kopiujemy folder z patchem do środka (żeby był dostępny ręcznie), ale go nie uruchamiamy
COPY ./patch_final /usr/src/prestashop_patch

# 2. Ustawiamy podstawowe uprawnienia, żeby Presta mogła działać
RUN chown -R www-data:www-data /var/www/html

# 3. Brak ENTRYPOINT - Presta wystartuje swoim domyślnym skryptem
