FROM ubuntu:latest

ENV DEBIAN_FRONTEND noninteractive
ENV HOME /root

EXPOSE 2022

RUN apt update && apt install python3 python3-dev python3-pip -y

RUN pip3 install pythread pysocket pyrandom

COPY ./flag.py /flag.py
COPY ./loyalty_test.py /loyalty_test.py
COPY ./run.sh /run.sh

RUN chmod +x /run.sh
RUN chmod +x /loyalty_test.py
RUN chmod +x /flag.py

CMD /run.sh