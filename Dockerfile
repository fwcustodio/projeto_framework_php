FROM nyanpass/php5.5:5.5-apache
COPY . /var/www/html/

RUN chmod 777 -R -f "/var/www/html/engine_site" 

EXPOSE 80