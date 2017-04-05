#!/bin/bash

sudo cat >> /etc/apt/sources.list.d/nodesource.list<<EOL
deb https://deb.nodesource.com/node_6.x xenial main
deb-src https://deb.nodesource.com/node_6.x xenial main
EOL

curl -s https://deb.nodesource.com/gpgkey/nodesource.gpg.key | sudo apt-key add -
sudo apt-get update
sudo apt-get install nodejs -y