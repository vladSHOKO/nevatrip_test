FROM serversideup/php:8.4-fpm-nginx

USER www-data

WORKDIR /var/www/html

COPY . /var/www/html

