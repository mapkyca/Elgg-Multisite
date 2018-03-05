#!/usr/bin/env bash

echo "Building base system..."
export DEBIAN_FRONTEND=noninteractive
apt-get clean
apt-get update
dpkg --configure -a
apt-get install -y --force-yes apache2 php7.0 php7.0-curl php7.0-cli php7.0-gd php7.0-mysql libapache2-mod-php7.0 php7.0-pdo-mysql php7.0-xmlrpc php7.0-mbstring php7.0-dom php7.0-exif php7.0-simplexml
apt-get install -y --force-yes mariadb-server mariadb-client  screen
apt-get install -y --force-yes gcc g++ make

if ! [ -L /var/www ]; then
  rm -rf /var/www
  mkdir -p /var/www
  ln -fs /home/vagrant/ /var/www/html
fi

echo "Configuring apache..."
a2enmod rewrite
cp -f /home/vagrant/provisioning/000-default.conf /etc/apache2/sites-available/
cp -f /home/vagrant/provisioning/multisite.conf /etc/apache2/sites-available/
a2ensite multisite

echo "Restarting apache..."
/etc/init.d/apache2 restart


echo "Installing elgg multisite schema..."
echo "create database if not exists elggmultisite;" | mysql -u root
echo "grant all on elggmultisite.* to 'elggmultisite'@'localhost' identified by 'elggmultisite';" | mysql -u root 
cat /home/vagrant/multisite/schema/multisite_mysql.sql | mysql -u root elggmultisite