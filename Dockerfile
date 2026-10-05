FROM php:8.3-cli-bookworm AS php-base

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libonig-dev \
        libsqlite3-dev \
        libxml2-dev \
        unzip \
    && docker-php-ext-install mbstring pdo_sqlite dom xml \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

FROM php-base AS composer-deps

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts

FROM node:22-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY resources ./resources
COPY public ./public
COPY vite.config.js tailwind.config.js postcss.config.js jsconfig.json ./
COPY --from=composer-deps /var/www/html/vendor/tightenco/ziggy ./vendor/tightenco/ziggy

RUN npm run build

FROM php-base AS app

WORKDIR /var/www/html

COPY . .
COPY --from=composer-deps /var/www/html/vendor ./vendor

RUN composer dump-autoload --no-dev --no-interaction --optimize \
    && mkdir -p \
        storage/app/docker \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

COPY --from=frontend --chown=www-data:www-data /app/public/build ./public/build
COPY --chmod=755 docker/entrypoint.sh /usr/local/bin/docker-entrypoint

USER www-data

EXPOSE 8000

ENTRYPOINT ["docker-entrypoint"]
