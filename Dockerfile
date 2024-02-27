FROM php:7.4-fpm-alpine

RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.ustc.edu.cn/g' /etc/apk/repositories \
    && apk add --update --no-cache nginx bash \
    && apk add --no-cache freetype-dev libjpeg-turbo-dev libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_mysql pcntl

COPY src /var/www/html

RUN chown -R www-data:www-data /var/www/html

WORKDIR /var/www/html

ENTRYPOINT ["/bin/sh", "-c", "set -e && php-fpm & php think worker:gateway -d & nginx -g 'daemon off;'"]
