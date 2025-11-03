#!/bin/bash

# Create naijaedupact folder if not existing
DIR="/var/www/naijaedupact"
if [ -d "$DIR" ]; then
  echo "${DIR} exists"
else
  echo "Creating ${DIR} directory"
  sudo mkdir ${DIR}
fi

#grant permission to files inside naijaedupact folder
sudo chmod -R 775 /var/www/naijaedupact

#Grant permission to files in required folder
sudo chown -R www-data.www-data /var/www/naijaedupact/storage && sudo chown -R www-data.www-data /var/www/naijaedupact/bootstrap/cache && sudo chown -R www-data.www-data /var/www/naijaedupact/public


#Cd in project folder
cd /var/www/naijaedupact

sudo chmod -R 775 storage && sudo chmod -R 775 bootstrap/cache && sudo chmod -R 775 public


