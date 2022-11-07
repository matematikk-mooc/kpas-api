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
RUN npm install
RUN npm run build

FROM php:8.1-fpm
RUN apt-get update && apt-get install -y \
		libfreetype6-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
    curl \
    nginx \
    supervisor

#RUN docker-php-ext-install mbstring curl mysqli openssl ctype intl xmlreader xml session phar curl

# Setup document root
WORKDIR /var/www/html

RUN mkdir /run/php
RUN chown www-data /run/php

# Create custom directory for phpsession
RUN mkdir -p /tmp/php/session/save_path
RUN mkdir -p /tmp/php/session/cookie_path
RUN chown www-data /tmp/php/session/save_path /tmp/php/session/cookie_path


# Create symlink so programs depending on `php` still function
RUN ln -s /usr/bin/php81 /usr/bin/php


# Make sure files/folders needed by the processes are accessable when they run under the www-data user
RUN chown -R www-data /var/www/html /run /var/lib/nginx /var/log/nginx


# Switch to use a non-root user from here on
USER www-data

# Add application
COPY --from=nodeBuild --chown=www-data /var/www/html /var/www/html

# Configure nginx
COPY --chown=www-data docker-prod/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY --chown=www-data docker-prod/fpm-pool.conf /usr/local/etc/php/php-fpm.d/www.conf
COPY --chown=www-data docker-prod/php.ini /usr/local/etc/php/conf.d/custom.ini

# Configure supervisord
COPY --chown=www-data docker-prod/supervisord.conf /etc/supervisor/conf.d/supervisord.conf


# Expose the port nginx is reachable on
EXPOSE 8080

RUN chmod +x /var/www/html/startup.prod.sh



# Let supervisord start nginx & php-fpm
ENTRYPOINT ["/var/www/html/startup.prod.sh"]
