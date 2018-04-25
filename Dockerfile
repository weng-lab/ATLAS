FROM php:5-apache

RUN docker-php-ext-install mysqli

COPY ./www/     /var/www/html/
