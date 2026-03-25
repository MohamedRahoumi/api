FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    bash curl git unzip zip postgresql-dev \
    nodejs npm build-base autoconf re2c \
    libtool make pkgconfig zlib-dev \
    oniguruma-dev libxml2-dev

RUN docker-php-ext-install pdo pdo_pgsql pgsql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Zidna hadchi l'Production
COPY . .
RUN composer install --no-dev --optimize-autoloader

# Khasna n3tiw permissions l storage o cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Nkhdmo b artisan serve f Railway (Ola t9dar tdir Nginx/Octane)
CMD php artisan serve --host=0.0.0.0 --port=$PORT
