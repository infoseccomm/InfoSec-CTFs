FROM ubuntu:latest

ENV HOME /root
ENV DEBIAN_FRONTEND noninteractive
EXPOSE 80

RUN apt update
RUN apt install -y php php-dev apache2 libapache2-mod-php
RUN echo "flag{0h_y0u_f0und_m3_51r!}" >> /etc/motd

COPY ./run.sh /run.sh
COPY ./html/ /var/www/html

RUN rm /var/www/html/index.html
RUN chmod +x /run.sh

CMD /run.sh
