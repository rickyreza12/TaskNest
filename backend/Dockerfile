FROM php:8.1-apache

#instal system dependencies
RUN apt-get update && apt-get install -y \
    unzip \ 
    zip \
    git \
    libzip-dev \
    libicu-dev \
    libonig-dev

# Install extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql zip intl

#install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html
