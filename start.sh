#!/bin/bash
mkdir -p /var/www/html/database
touch /var/www/html/database/database.sqlite
php artisan migrate --force
php artisan serve --host=0.0.0.0 --port=8000
