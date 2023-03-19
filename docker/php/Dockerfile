FROM php:8.1-fpm

RUN apt-get update && \
    apt-get install -y curl zip unzip && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    docker-php-ext-install pdo_mysql

WORKDIR /var/www/api

COPY . /var/www/api