#!/bin/bash

# ApplicationStart script for naijaedupact application
cd /var/www/naijaedupact
echo 'App starting...'

# echo '📦 Running Laravel migrations + seeding...'
# if php artisan migrate:fresh --seed --force; then
#     echo "✅ Migrations and seeding complete."
# else
#     echo "❌ Migration failed."
#     exit 1
# fi

# Building frontend
sudo npm run build

echo '✅ Application started successfully!'
