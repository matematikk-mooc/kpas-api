#!/bin/bash

echo $0
echo "Copy all files in /lticonfig to correct path"
echo "==============="
cp -a /lticonfig/. /var/www/html/database

echo $0
echo "Copy all files in /ltijwt to correct path"
echo "==============="
cp -a /ltijwt/. /var/www/html/app/Ltiv3/jwt_key_kpas

echo $0
echo "Inject canvas url from ENV Variable to nginx-config"
echo "==============="
sed 's@startup_prod:INJECT_CANVAS_HOST@'"$CANVAS_HOST"'@' /etc/nginx/nginx.conf >/etc/nginx/nginx.conf.temp
cp /etc/nginx/nginx.conf.temp /etc/nginx/nginx.conf

echo $0
echo "Run PHP artisan commands"
echo "==============="

su -s /bin/bash -c "
    php artisan cache:clear &&
    php artisan route:clear &&
    php artisan view:clear &&
    php artisan config:cache &&
    php artisan route:cache &&
    php artisan view:cache &&
    php artisan migrate --force
" www-data
storageDir="/var/www/html/storage"
chown -R 1000:1000 $storageDir
chmod -R u+rw,g+rw $storageDir

echo $0
echo "Start sshd"
echo "==============="
/usr/sbin/sshd

echo $0
echo "Start Supervisor with Nginx and PHP-FPM"
echo "==============="
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
