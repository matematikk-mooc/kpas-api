# COMPOSER INSTALL
FROM composer:2.4.3 AS composerBuild
COPY . /var/www/html
WORKDIR /var/www/html
RUN composer install \
                --no-dev \
                --prefer-dist \
                --no-interaction \
                --optimize-autoloader

# NPM INSTALL + COMPILE ASSETS
FROM node:16-alpine3.15 AS nodeBuild
COPY --from=composerBuild /var/www/html /var/www/html
WORKDIR /var/www/html
RUN npm install
RUN npm run build


# SERVER
FROM php:8.1-fpm

#Install packages
RUN apt-get update && apt-get install -y \
    cron \
    vim \
	libpng-dev \
    zlib1g-dev \
    libzip-dev \
    curl \
    nginx \
    supervisor

RUN docker-php-ext-install zip gd mysqli pdo pdo_mysql

WORKDIR /var/www/html

# Add application
COPY --from=nodeBuild --chown=www-data /var/www/html /var/www/html

# Make sure files/folders needed by the processes are accessable when they run under the www-data user
RUN chown -R www-data /run /var/lib/nginx /var/log/nginx

# Configure nginx
COPY --chown=www-data docker-prod/nginx.conf /etc/nginx/nginx.conf
# The www-data user must be able to write to a temporary nginx.conf-file, since it will be using
# sed -i during startup.prod.
RUN touch /etc/nginx/nginx.conf.temp
RUN chown www-data /etc/nginx/nginx.conf.temp

# Configure PHP-FPM
COPY --chown=www-data docker-prod/fpm-pool.conf /usr/local/etc/php/php-fpm.d/www.conf
COPY --chown=www-data docker-prod/php.ini /usr/local/etc/php/conf.d/custom.ini

# Configure supervisord
COPY --chown=www-data docker-prod/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Configure cron
COPY --chown=www-data docker-prod/laravel-cron /etc/cron.d/laravel-cron
# Give execution rights on the cron job
RUN chmod gu+rw /var/run
RUN chmod gu+s /usr/sbin/cron
RUN chown www-data /etc/cron.d/laravel-cron
RUN chmod 0644 /etc/cron.d/laravel-cron
# Apply cron job
RUN crontab -u www-data /etc/cron.d/laravel-cron
# Create the log file to be able to run tail
RUN touch /var/log/cron.log
RUN chown www-data /var/log/cron.log


# Switch to use a non-root user
USER www-data

# Expose the port nginx is reachable on
EXPOSE 8080

RUN chmod +x /var/www/html/startup.prod.sh

# Let supervisord start nginx & php-fpm
ENTRYPOINT ["/var/www/html/startup.prod.sh"]
