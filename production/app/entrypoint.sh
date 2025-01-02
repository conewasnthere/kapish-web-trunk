#!/bin/bash

# cache and optimize
php /var/www/html/artisan optimize --force
php /var/www/html/artisan config:cache
php /var/www/html/artisan route:cache
php /var/www/html/artisan view:cache

# fix permissions
chown -R www-data:www-data /var/www/html
chmod -R 777 /var/lib/nginx

# boot up
/usr/bin/supervisord -c /etc/supervisor.d/supervisord.ini
