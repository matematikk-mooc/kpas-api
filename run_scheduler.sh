cd /home/site/wwwroot && \
php artisan fetch_from:nsr && \
php artisan schedule:run >>/dev/null 2>&1
