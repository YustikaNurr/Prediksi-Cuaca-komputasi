# Dockerfile (production-ready, single-stage build)
FROM php:8.1-fpm

# instal system deps
RUN apt-get update && apt-get install -y \
    git zip unzip libzip-dev libonig-dev libpng-dev libjpeg-dev libpq-dev \
 && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath \
 && pecl install xdebug || true \
 && rm -rf /var/lib/apt/lists/*

# instal composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# copy composer files & install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-plugins --no-scripts --prefer-dist --no-interaction

# copy app
COPY . .

# set permissions (sesuaikan sesuai host)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
