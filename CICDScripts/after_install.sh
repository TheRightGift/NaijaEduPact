#!/bin/bash

#cd to project folder
cd /var/www/naijaedupact

# Run migration
# echo "Running migrations"

echo '⚡ Clearing & optimizing Laravel cache...'
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan schedule:clear-cache
