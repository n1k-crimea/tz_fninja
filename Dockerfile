FROM php:8.2-fpm

# Run PHP-FPM as a user matching the host owner of the mounted source,
# so the container can write to storage/ and bootstrap/cache/.
ARG UID=1000
ARG GID=1000

# System dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions required by Laravel + Filament + MySQL
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Align the built-in www-data user/group with the host UID/GID
RUN groupmod -o -g ${GID} www-data \
    && usermod -o -u ${UID} -g ${GID} www-data

WORKDIR /var/www

# Install PHP dependencies first (better layer caching)
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-interaction --prefer-dist --no-security-blocking

# Copy application source
COPY . .

RUN composer dump-autoload --optimize \
    && chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

USER www-data

EXPOSE 9000

CMD ["php-fpm"]
