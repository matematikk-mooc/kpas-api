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

FROM alpine:3.16.2
# Setup document root
WORKDIR /var/www/html

# Install packages and remove default server definition
RUN apk add --no-cache \
  php81-fpm \
  php81-pdo \
  php81-tokenizer \
  php81-mbstring \
  php81-curl \
  php81-mysqli \
  php81-openssl \
  php81-gd \
  php81-ctype \
  php81-intl \
  php81-xmlreader \
  php81-xml \
  php81-session \
  php81-phar \
  php81-pdo_mysql \
  curl \
  nginx \
  supervisor \
  bash
  
# Create custom directory for phpsession
RUN mkdir -p /tmp/php/session/save_path
RUN mkdir -p /tmp/php/session/cookie_path
RUN chown nobody.nobody /tmp/php/session/save_path /tmp/php/session/cookie_path

# Create symlink so programs depending on `php` still function
RUN ln -s /usr/bin/php81 /usr/bin/php

# Configure nginx
COPY docker-prod/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY docker-prod/fpm-pool.conf /etc/php81/php-fpm.d/www.conf
COPY docker-prod/php.ini /etc/php81/conf.d/custom.ini

# Configure supervisord
COPY docker-prod/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R nobody.nobody /var/www/html /run /var/lib/nginx /var/log/nginx

# Switch to use a non-root user from here on
USER nobody

# Add application
COPY --from=nodeBuild --chown=nobody /var/www/html /var/www/html


# Expose the port nginx is reachable on
EXPOSE 8080

RUN chmod +x /var/www/html/startup.prod.sh


# Let supervisord start nginx & php-fpm
CMD ["/var/www/html/startup.prod.sh"]
