FROM php:8.0-apache
WORKDIR /var/www/html

COPY ./robots.txt /var/www/html/robots.txt
COPY ./secretdev.php /var/www/html/secretdev.php
COPY ./main.html /var/www/html/main.html
COPY ./index.html /var/www/html/index.html
COPY ./error.html /var/www/html/error.html
COPY ./flag.txt /var/www/flag.txt

EXPOSE 80
