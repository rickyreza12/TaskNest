FROM php:8.1-fpm-alpine

# Install PHP extensions
RUN apk add --no-cache \
    bash \
    git \
    unzip \
    zip \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    rabbitmq-c-dev \
    autoconf \
    gcc \
    g++ \
    make

# Install PHP extensions
RUN docker-php-ext-install \
    bcmath \
    sockets \
    mysqli \
    pdo \
    pdo_mysql \
    zip \
    intl

# Install php-amqp extension
RUN pecl install amqp \
    && docker-php-ext-enable amqp

#install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html
