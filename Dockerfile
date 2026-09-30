FROM node:22-bookworm-slim AS assets
WORKDIR /src
COPY public_html/package*.json ./
RUN npm ci --ignore-scripts
COPY public_html/ ./
RUN npm run vendor && node scripts/package-release.cjs /release

FROM php:8.4-apache-bookworm
RUN apt-get update \
    && apt-get install -y --no-install-recommends libpng-dev libjpeg62-turbo-dev libfreetype6-dev libonig-dev libxml2-dev unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql gd mbstring dom opcache \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY docker/php.ini /usr/local/etc/php/conf.d/scl.ini
COPY docker/apache.conf /etc/apache2/conf-available/scl.conf
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" && a2enconf scl
WORKDIR /var/www/html
COPY --from=assets /release/ ./
COPY public_html/deploy/ ./deploy/
COPY public_html/scripts/migrate-resumes.php ./scripts/migrate-resumes.php
RUN composer install --working-dir=_app --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader \
    && composer check-platform-reqs --working-dir=_app --no-dev \
    && mkdir -p /srv/santa-casa-private \
    && chown -R www-data:www-data arquivos /srv/santa-casa-private \
    && chmod 750 /srv/santa-casa-private
