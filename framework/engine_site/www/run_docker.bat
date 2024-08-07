docker build -t my-php-app .
docker run -d -p 80:80 --name my-running-app my-php-app