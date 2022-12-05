FROM ubuntu:16.04

ENV HOME /root
ENV PHANTOM_JS phantomjs-2.1.1-linux-x86_64
ENV DEBIAN_FRONTEND noninteractive
ENV FLAG /app/flag.txt

EXPOSE 80

RUN apt update && apt install mysql-server php php-dev apache2 libapache2-mod-php php-mysql nodejs npm wget build-essential chrpath libssl-dev libxft-dev libfreetype6 libfreetype6-dev libfontconfig1 libfontconfig1-dev -y
RUN ln -s /usr/bin/nodejs /usr/bin/node
RUN npm install -g html-pdf@2.2.0

RUN wget https://github.com/Medium/phantomjs/releases/download/v2.1.1/$PHANTOM_JS.tar.bz2
RUN tar xvjf $PHANTOM_JS.tar.bz2
RUN mv $PHANTOM_JS /usr/local/share
RUN ln -sf /usr/local/share/$PHANTOM_JS/bin/phantomjs /usr/local/bin

COPY ./flag.txt /app/flag.txt
COPY ./pdf.js /usr/local/lib/node_modules/html-pdf/lib/pdf.js
COPY ./run.sh /run.sh
COPY ./table /table
COPY ./html/ /var/www/html/

RUN echo "[mysqld]" >> /etc/mysql/my.cnf
RUN echo "sql_mode = 'ONLY_FULL_GROUP_BY,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'" >> /etc/mysql/my.cnf
RUN rm /var/www/html/index.html
RUN chmod -R 777 /var/www/html/storage/
RUN chmod +x /run.sh
RUN chmod +x /table
RUN echo "extension=php_mysqli.dll" >> /etc/php/7.0/apache2/php.ini

RUN useradd -ms /bin/bash flag
RUN mkdir /home/flag/.ssh/
RUN touch /home/flag/.ssh/id_rsa
RUN echo "Sorry, no ssh key here. Take the flag instead:" >> /home/flag/.ssh/id_rsa
RUN echo "flag{d0n'7_7runc473_y0ur_570r135}" >> /home/flag/.ssh/id_rsa 

CMD /run.sh
