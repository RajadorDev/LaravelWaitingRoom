FROM php:8.3-fpm AS waiting_room_laravel

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libzip-dev \
    zlib1g-dev \
    nodejs \
    npm

WORKDIR /var/www

COPY . /var/www

COPY ./docker/php/production.ini /usr/local/etc/php/conf.d/production.ini

RUN mkdir -p /tmp && chmod 1777 /tmp

RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/database

RUN docker-php-ext-install gd && docker-php-ext-install zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer run build

CMD [ "php-fpm" ]

EXPOSE 9000

FROM nginx:alpine AS nginx_server

COPY --from=waiting_room_laravel /var/www /var/www

COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf
