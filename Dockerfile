FROM composer:latest as builder

FROM php:8.3-fpm-alpine
RUN apk add --no-cache git unzip
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html
COPY --from=builder /usr/bin/composer /usr/local/bin/composer
COPY . .
RUN composer install 
