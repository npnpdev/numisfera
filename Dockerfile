FROM prestashop/prestashop:8.1-apache

# Kopiujemy Twoje pliki (razem z tym parameters.php, który przed chwilą zrobiłeś)
COPY ./prestashop/ /var/www/html/

# USUWAMY FOLDER INSTALL - to wyłączy asystenta instalacji raz na zawsze
RUN rm -rf /var/www/html/install

# Naprawiamy uprawnienia
RUN chown -R www-data:www-data /var/www/html

# Wyłączamy auto-instalator, bo mamy już własny plik konfiguracyjny
ENV PS_INSTALL_AUTO=0
