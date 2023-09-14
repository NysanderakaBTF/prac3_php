FROM php:8.1-apache

RUN docker-php-ext-install mysqli && \
    apt update && \
    apt install -y libaprutil1-dbd-mysql && \
    a2enmod authn_dbd && \
    service apache2 restart