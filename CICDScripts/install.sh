#!/bin/bash

#install all node packages
cd /var/www/naijaedupact

#install all composer packages
# echo "Updating composer packages"
# sudo composer install
# sudo composer update

#Update node
# echo "Updating node packages"
# sudo npm install

#Run migrations and seeders
echo "Running migrations and seeders"
sudo php artisan migrate:fresh --seed





