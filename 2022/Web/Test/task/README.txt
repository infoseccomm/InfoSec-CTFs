docker build -t my-apache2 .
docker run -dit --name my-running-app -p 8181:80 my-apache2
