#!/bin/bash

echo $0
echo "Copy all files in /lticonfig to correct path"
echo "==============="
cp -a /lticonfig/. /var/www/html/database

echo $0
echo "Inject canvas url from ENV Variable to nginx-config"
echo "==============="
sed 's@startup_prod:INJECT_CANVAS_HOST@'"$CANVAS_HOST"'@' /etc/nginx/nginx.conf > /etc/nginx/nginx.conf.temp
cp /etc/nginx/nginx.conf.temp /etc/nginx/nginx.conf


echo $0
echo "Artisan migrate"
echo "==============="
php artisan migrate --force

echo $0
echo "Artisan cache clear"
echo "==============="
php artisan cache:clear

echo $0
echo "Artisan cache config"
echo "==============="
php artisan config:cache

echo $0
echo "Artisan route clear"
echo "==============="
php artisan route:clear

echo $0
echo "Artisan route cache"
echo "==============="
php artisan route:cache

echo $0
echo "Artisan view clear"
echo "==============="
php artisan view:clear

echo $0
echo "Artisan view cache"
echo "==============="
php artisan view:cache

echo $0
echo "Start sshd"
echo "==============="
/usr/sbin/sshd -D

echo $0
echo "Start Supervisor with Nginx and PHP-FPM"
echo "==============="
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
