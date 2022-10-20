FROM composer:2.4.3 AS composerBuild
COPY . /var/www/html
WORKDIR /var/www/html
RUN composer install \
                --no-dev \
                --prefer-dist \
                --no-interaction \
                --optimize-autoloader

FROM node:16-alpine3.15 AS nodeBuild
COPY --from=composerBuild /var/www/html /var/www/html
WORKDIR /var/www/html
# TODO: ADD BUILD STEP, NPM
#RUN npm install
#RUN npm run build

FROM php:8.1-apache

# Set document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Add docker php ext repo
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Install php extensions
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions mbstring pdo_mysql zip exif pcntl gd memcached

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    unzip \
    git \
    curl \
    lua-zlib-dev

# Add user for laravel application
#RUN groupadd -g 1000 www
#RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy code to /var/www
COPY --from=nodeBuild --chown=www-data:www-data /var/www/html /var/www/html

RUN chmod -R ug+w /var/www/html/storage

#Disable access logging to STDOUT to make kubectl logs more useful
RUN sed -ri -e 's!CustomLog.*!#CustomLog!g' /etc/apache2/sites-enabled/*.conf
RUN sed -ri -e 's!CustomLog.*!#CustomLog!g' /etc/apache2/conf-enabled/*.conf

# Allow rewrite module
RUN a2enmod rewrite

WORKDIR /var/www/html

RUN cp environments/production/.env .env

RUN chown www-data:www-data startup.prod.sh
RUN chmod +x startup.prod.sh

USER www-data
EXPOSE 80
