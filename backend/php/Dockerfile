FROM php:8.4-fpm-alpine

# Dependencias del sistema (zip, intl, etc.) + herramientas + PostgreSQL
RUN apk add --no-cache \
    bash \
    git \
    unzip \
    icu-dev \
    oniguruma-dev \
    libzip-dev \
    mysql-client \
    postgresql-dev \
  && docker-php-ext-configure intl \
  && docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    intl \
    zip \
  && rm -rf /var/cache/apk/*

# Composer (copiado desde la imagen oficial)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www