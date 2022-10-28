#!/usr/bin/env bash
echo $0
echo "Node version should be 16 according to KURSP-581"
echo "================================================"
node -v

echo $0
echo "Composer install"
echo "================"
composer install --no-dev

echo $0
echo "Artisan set production"
echo "======================"
php artisan env:set production -n --env local

echo $0
echo "Artisan generate key"
echo "===================="
php artisan key:generate -n --env local

echo $0
echo "Artisan migrate"
echo "==============="
php artisan migrate --seed --force

echo $0
echo "npm install"
echo "==========="
npm install

echo $0
echo "npm run build"
echo "=================="
npm run build
