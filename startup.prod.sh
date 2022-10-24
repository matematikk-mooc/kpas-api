#!/usr/bin/env bash


echo $0
echo "Artisan migrate"
echo "==============="
php artisan migrate --force

echo $0
echo "Artisan cache config"
echo "==============="
php artisan config:cache

echo $0
echo "Artisan route cache"
echo "==============="
php artisan route:cache

echo $0
echo "Artisan view cache"
echo "==============="
php artisan view:cache

echo $0
echo "Start Apache Web Server"
echo "==============="
/usr/sbin/apache2ctl -D FOREGROUND
