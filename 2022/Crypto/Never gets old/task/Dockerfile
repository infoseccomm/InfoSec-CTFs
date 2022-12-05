FROM ubuntu:latest

ENV DEBIAN_FRONTEND noninteractive
ENV HOME /root

EXPOSE 2022

RUN apt update && apt install python3 python3-dev python3-pip -y

RUN pip3 install pycryptodome pythread pysocket arrow

COPY ./flag.py /flag.py
COPY ./never_gets_old.py /never_gets_old.py
COPY ./run.sh /run.sh

RUN chmod +x /run.sh
RUN chmod +x /never_gets_old.py
RUN chmod +x /flag.py

CMD /run.sh