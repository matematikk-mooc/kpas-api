FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    jq \
    nano \
    curl \
    nginx \
    supervisor \
    zip \
    unzip \
    vim-common \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zlib1g-dev \
    openssh-client \
    openssl && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install gd \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install pdo \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install exif \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install opcache

##############################################################
# Tools
##############################################################

# 1. Install NVM
ENV NVM_DIR /usr/local/nvm
RUN mkdir -p $NVM_DIR \
    && export NVM_DIR=$NVM_DIR \
    && curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash

# 2. Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

##############################################################
# Startup
##############################################################

WORKDIR /var/www/html
USER root
ENV NVM_DIR /usr/local/nvm

EXPOSE 8080
ENTRYPOINT ["/var/www/html/.docker/startup.sh"]
