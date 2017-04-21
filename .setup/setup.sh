#!/bin/bash

sudo apt-get update

# Install php and php extensions
/var/www/.setup/php.sh
# Install composer
/var/www/.setup/composer.sh
# Install apache2
/var/www/.setup/apache2.sh
# Install nodeJS
/var/www/.setup/node.sh
# Install mysql
/var/www/.setup/mysql.sh

# Install composer dependencies
cd /var/www
composer install

# Install nodejs dependencies
npm install -f --no-bin-links

# Import database
mysql -uroot -pvagrant < database.sql

# Restart apache2
sudo service apache2 restart