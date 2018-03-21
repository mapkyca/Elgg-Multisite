# Usage

The basic concept is that you set up one PRIVATE domain/site on your apache host
that has ```/multisite/``` as its docroot, then set up a PUBLIC wildcard domain which has ```/elgg/```
as its docroot.

If you want an example of how to set it up, take a look at the configuration of the vagrant build.

## Basic Setup

* Create a database and install ```multisite/schema/multisite_mysql.sql```
* Create a database user with
  * The ability to create databases
  * The ability to grant privileges on those databases
* Modify the ```$CONFIG->multisite->db_*``` configuration in elgg/elgg-config/settings.php with these database settings
* Configure a PRIVATE domain to use ```/multisite/``` as a docroot
* Configure a PUBLIC wildcard domain to use ```/elgg/``` docroot
* Make sure ```/data/``` is writable by your webserver
* Visit your private domain & set up your first user and user domains

## Vagrant build

If you want to start playing with this quickly, you can run the vagrant build.

* Install vagrant
* Modify your ```hosts``` file (/etc/hosts on linux)
  * Set "elgg-multisite" to point to the IP in your Vagrantfile (192.168.33.35 by default)
  * Set "whatever.multi", "whatever2.multi", etc... to point to the same
* Make sure ```/data/``` is writable by your webserver
* ```vagrant up```
* Go to http://elgg-multisite and set up your admin user
  * Log in admin
  * Create your whatever.multi domain
* Visit http://whatever.multi

Note: Provisioning will destroy any existing management database, so careful if you're re-provisioning an existing box!