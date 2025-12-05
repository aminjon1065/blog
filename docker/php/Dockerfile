# docker/php/Dockerfile
FROM php:8.4-fpm-alpine
# Устанавливаем всё нужное + дополнительные dev-пакеты для компиляции
RUN apk add --no-cache \
        git \
        curl \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        zip \
        unzip \
        oniguruma-dev \
        libzip-dev \
        icu-dev \
        nodejs \
        npm \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        icu-dev \
        libzip-dev \
        oniguruma-dev \
        autoconf \
        g++ \
        make \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        bcmath \
        gd \
        zip \
        intl \
        opcache \
        exif \
        pcntl \
    && apk del .build-deps \
    && rm -rf /var/cache/apk/* /tmp/*

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Конфиги PHP (если есть)
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/zz-custom.conf

# production ini
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www

# Кешируем composer
COPY backend/composer.json backend/composer.lock* ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Копируем код
COPY backend .

# Права
RUN chown -R www-data:www-data \
        /var/www/storage \
        /var/www/bootstrap/cache

CMD ["php-fpm"]