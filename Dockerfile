
# ==========================================
# Stage 1: Composer Dependencies
# ==========================================
FROM php:8.4-cli-bookworm AS vendor

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    libpq-dev \
    libonig-dev \
    && docker-php-ext-install -j1 \
        pdo_pgsql \
        pgsql \
        bcmath \
        intl \
        pcntl \
        zip \
        mbstring \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN php -v \
    && composer --version \
    && composer install \
        --no-dev \
        --no-interaction \
        --prefer-dist \
        --no-progress \
        --no-scripts \
        --optimize-autoloader


# ==========================================
# Stage 2: Laravel Application
# ==========================================
FROM php:8.4-fpm-bookworm AS app

RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    libpq-dev \
    libonig-dev \
    && docker-php-ext-install -j1 \
        pdo_pgsql \
        pgsql \
        bcmath \
        intl \
        pcntl \
        zip \
        mbstring \
        opcache \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copy application source
COPY . .

# Copy Composer dependencies
COPY --from=vendor /var/www/html/vendor ./vendor

# Laravel production environment
ENV APP_ENV=production
ENV APP_DEBUG=false

# PHP OPcache configuration
RUN { \
    echo "opcache.enable=1"; \
    echo "opcache.enable_cli=0"; \
    echo "opcache.memory_consumption=128"; \
    echo "opcache.interned_strings_buffer=16"; \
    echo "opcache.max_accelerated_files=20000"; \
    echo "opcache.validate_timestamps=0"; \
    } > /usr/local/etc/php/conf.d/opcache.ini

# Create Laravel required directories and permissions
RUN mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data \
        storage \
        bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Laravel package discovery and optimization
RUN php artisan package:discover --ansi \
    && php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear

EXPOSE 9000

CMD ["php-fpm"]
