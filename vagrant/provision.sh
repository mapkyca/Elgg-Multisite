#!/usr/bin/env bash

echo "Building base system..."
export DEBIAN_FRONTEND=noninteractive
apt-get clean
apt-get update
dpkg --configure -a
apt-get install -y --force-yes software-properties-common
add-apt-repository -y ppa:ondrej/php
apt-get update
apt-get install -y --force-yes apache2 php7.4 php7.4-curl php7.4-cli php7.4-gd php7.4-mysql libapache2-mod-php7.4 php7.4-pdo-mysql php7.4-xmlrpc php7.4-mbstring php7.4-dom php7.4-exif php7.4-simplexml
apt-get install -y --force-yes mariadb-server mariadb-client  screen
apt-get install -y --force-yes phpmyadmin
apt-get install -y --force-yes gcc g++ make

if ! [ -L /var/www ]; then
  rm -rf /var/www
  mkdir -p /var/www
  ln -fs /home/vagrant/multisite/ /var/www/html
fi

echo "Configuring apache..."
a2enmod rewrite
cp -f /home/vagrant/multisite/vagrant/000-default.conf /etc/apache2/sites-available/
cp -f /home/vagrant/multisite/vagrant/multisite.conf /etc/apache2/sites-available/

ln -s /etc/phpmyadmin/apache.conf /etc/apache2/conf-available/phpmyadmin.conf

a2enconf phpmyadmin
a2ensite multisite

usermod -a -G vagrant www-data

echo "Restarting apache..."
/etc/init.d/apache2 restart


echo "Installing elgg multisite schema..."
echo "drop database elggmultisite" | mysql -u root
echo "drop user 'elggmultisite'@'localhost'" | mysql -u root
echo "create database if not exists elggmultisite;" | mysql -u root
echo "create user 'elggmultisite'@'localhost' identified by 'elggmultisite';" | mysql -u root 
echo "grant all privileges on * . * to  'elggmultisite'@'localhost' with grant option;" | mysql -u root 
echo "grant all privileges on * . * to  'elggmultisite'@'%' with grant option;" | mysql -u root 
echo "flush privileges;" | mysql -u root 
#echo "grant all on elggmultisite.* to elggmultisite@localhost identified by 'elggmultisite' with grant option;" | mysql -u root 
#echo "grant all on elggmultisite.* to elggmultisite@'%' identified by 'elggmultisite' with grant option;" | mysql -u root 
cat /home/vagrant/multisite/multisite/schema/multisite_mysql.sql | mysql -u root elggmultisite
echo "If you saw some errors about dropping databases or users, things probably went fine."

echo " "
echo "Multisite provisioned, visit http://elgg-multisite/ to start!"
