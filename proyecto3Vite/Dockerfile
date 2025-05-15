FROM node:18-bullseye AS node_builder

WORKDIR /var/www

# Laravel base
FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libzip-dev \
    zip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    && docker-php-ext-install pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install Node.js & NPM
COPY --from=node_builder /usr/local /usr/local

WORKDIR /var/www

# Start Laravel server
CMD ["sh", "-c", "composer install && php artisan serve --host=0.0.0.0 --port=8000"]