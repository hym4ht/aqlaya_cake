FROM php:8.2-cli AS php-build

WORKDIR /var/www

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libsqlite3-dev libzip-dev \
    && docker-php-ext-install bcmath pdo_sqlite zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN mkdir -p bootstrap/cache \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs \
    && touch database/database.sqlite \
    && composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

FROM node:20-alpine AS frontend-build

WORKDIR /var/www

COPY . .

RUN npm ci && npm run build

FROM php:8.2-apache

WORKDIR /var/www

ENV APACHE_DOCUMENT_ROOT=/var/www/public

RUN apt-get update \
    && apt-get install -y --no-install-recommends libsqlite3-dev libzip-dev unzip \
    && docker-php-ext-install bcmath pdo_sqlite zip \
    && a2enmod rewrite headers \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
        /etc/apache2/sites-available/*.conf \
        /etc/apache2/apache2.conf \
        /etc/apache2/conf-available/*.conf \
    && rm -rf /var/lib/apt/lists/*

COPY . .
COPY --from=php-build /var/www/vendor /var/www/vendor
COPY --from=frontend-build /var/www/public/build /var/www/public/build
COPY docker/entrypoint.sh /usr/local/bin/docker-entrypoint-app

RUN cp -a /var/www/storage /opt/storage-template \
    && chmod +x /usr/local/bin/docker-entrypoint-app \
    && chown -R www-data:www-data /var/www /opt/storage-template

EXPOSE 80

ENTRYPOINT ["docker-entrypoint-app"]
CMD ["apache2-foreground"]
