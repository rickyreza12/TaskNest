FROM php:8.1-fpm-alpine

# Install PHP extensions
RUN apk add --no-cache \
    bash \
    git \
    unzip \
    zip \
    libzip-dev \
    icu-dev \
    oniguruma-dev 

# Install extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql zip intl

#install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html
