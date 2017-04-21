#!/bin/bash

sudo apt-get install apache2 libapache2-mod-php -y

# Update apache2 configuration
sudo cat > /etc/apache2/sites-available/000-default.conf<<EOL
<VirtualHost *:80>
   ServerAdmin webmaster@localhost
   DocumentRoot /var/www

   <Directory /var/www/>
       AllowOverride All
   </Directory>

   ErrorLog ${APACHE_LOG_DIR}/error.log
   CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOL

sudo a2enmod rewrite