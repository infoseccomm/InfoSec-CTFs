FROM httpd:2.4
COPY ./my-httpd.conf /usr/local/apache2/conf/httpd.conf
COPY ./error.html /usr/local/apache2/htdocs/error.html
COPY ./public-html/ /usr/local/apache2/htdocs/
EXPOSE 80
