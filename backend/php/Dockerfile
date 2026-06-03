FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    bash \
    git \
    unzip \
    icu-dev \
    oniguruma-dev \
    libzip-dev \
    mysql-client \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    nodejs \
    npm \
  && docker-php-ext-configure intl \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    intl \
    zip \
    gd \
  && rm -rf /var/cache/apk/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

RUN composer install --optimize-autoloader --no-interaction
RUN npm install && npm run build

# Permisos necesarios para Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 8000
CMD php artisan config:clear && php artisan serve --host=0.0.0.0 --port=8000