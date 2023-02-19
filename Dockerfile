FROM php:7.4-fpm

RUN sed -i 's/deb.debian.org/mirrors.aliyun.com/g' /etc/apt/sources.list && \
    sed -i 's/security.debian.org/mirrors.aliyun.com/g' /etc/apt/sources.list && \
    apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        && docker-php-ext-configure gd \
            --with-freetype \
            --with-jpeg \
        && docker-php-ext-install gd pdo_mysql pcntl

WORKDIR /var/www/html