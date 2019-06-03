#!/usr/bin/env bash

composer install
php artisan env:set production
php artisan key:generate
