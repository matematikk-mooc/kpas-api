# COMPOSER INSTALL
FROM composer:2.7 AS composerBuild
COPY . /var/www/html
WORKDIR /var/www/html
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader

# NPM INSTALL + COMPILE ASSETS
FROM node:20.17-alpine3.19 AS nodeBuild
COPY --from=composerBuild /var/www/html /var/www/html
WORKDIR /var/www/html
RUN npm install
RUN npm run build


# SERVER
FROM php:8.3-fpm

#Install packages
RUN apt-get update && apt-get install -y \
    cron \
    vim \
    libpng-dev \
    zlib1g-dev \
    libzip-dev \
    curl \
    nginx \
    supervisor \
    ssh \
    openssh-server

RUN docker-php-ext-install zip gd mysqli pdo pdo_mysql

WORKDIR /var/www/html

# Add application
COPY --from=nodeBuild --chown=www-data /var/www/html /var/www/html

# Ensure Laravel storage directory structure exists
RUN mkdir -p /var/www/html/storage/framework/cache/data \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/views \
    && chown -R www-data:www-data /var/www/html/storage

# Make sure files/folders needed by the processes are accessable when they run under the www-data user
RUN chown -R www-data /run /var/lib/nginx /var/log/nginx

# -- CONFIGURE NGINX --
COPY --chown=www-data docker-prod/nginx.conf /etc/nginx/nginx.conf
# The www-data user must be able to write to a temporary nginx.conf-file, since it will be using
# sed -i during startup.prod.
RUN touch /etc/nginx/nginx.conf.temp
RUN chown www-data /etc/nginx/nginx.conf.temp

# -- CONFIGURE PHP-FPM --
COPY --chown=www-data docker-prod/fpm-pool.conf /usr/local/etc/php/php-fpm.d/www.conf
COPY --chown=www-data docker-prod/php.ini /usr/local/etc/php/conf.d/custom.ini

# -- CONFIGURE SUPERVISORD --
COPY --chown=www-data docker-prod/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# -- CONFIGURE SSH --
# Todo: If we want to change to www-data as user, we need to place the bash_profile in the home directory of www-data
COPY docker-prod/ssh_bash_profile /root/.bash_profile

# -- CONFIGURE SSH FOR AZURE APP SERVICE --
RUN chown -R www-data /etc/ssh/
RUN echo "root:Docker!" | chpasswd
COPY --chown=www-data docker-prod/sshd_config /etc/ssh/
RUN mkdir -p /tmp
COPY --chown=www-data docker-prod/ssh_setup.sh /tmp
RUN mkdir -p /var/run/sshd
RUN chmod +x /tmp/ssh_setup.sh \
    && (sleep 1;/tmp/ssh_setup.sh 2>&1 > /dev/null)


# Todo: As long as we are running in Azure App Service, we can run as root.
# We need to run as root in order to be able to run sshd.
#
# If we want to run this image in a kubernetes cluster, we need to run as a non-root user.
# Then, we don't need to run sshd, and we can remove the sshd_config and ssh_setup.sh files.
#
# Info: https://learn.microsoft.com/en-us/answers/questions/697997/how-to-set-up-ssh-for-a-linux-container-in-app-ser.html
#
# USER www-data

# -- PORT EXPOSURE --
# NGINX (8080) + SSH (2222)
EXPOSE 8080 2222

RUN chmod +x /var/www/html/startup.prod.sh

# Let supervisord start nginx & php-fpm
ENTRYPOINT ["/var/www/html/startup.prod.sh"]
