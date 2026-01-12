# Używamy wersji stabilnej. Ona NIE nadpisuje plików przy starcie!
FROM prestashop/prestashop:8.1-apache

# Kopiujemy pliki bezpośrednio do obrazu
COPY ./prestashop/ /var/www/html/

# Naprawiamy uprawnienia, żeby klaster ich nie odrzucił
RUN chown -R www-data:www-data /var/www/html

# Wyłączamy automatyczny instalator
ENV PS_INSTALL_AUTO=0
