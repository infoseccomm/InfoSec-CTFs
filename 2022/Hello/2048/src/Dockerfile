FROM php:7.0-apache  
COPY . /var/www/html
RUN chmod a+r /var/www/html/*
RUN chmod 755 /var/www/html/js
RUN chmod 644 /var/www/html/js/.htaccess 
RUN chmod 755 /var/www/html/meta
RUN chmod 644 /var/www/html/meta/.htaccess 
RUN chmod 755 /var/www/html/style
RUN chmod 644 /var/www/html/style/.htaccess 
RUN chmod 755 /var/www/html/style/fonts
RUN chmod 644 /var/www/html/style/fonts/.htaccess 
EXPOSE 80
