# Multisite for Elgg

## What is it?

MP Multisite for Elgg allows you to run multiple separate Elgg 
sites off of the same install of the codebase, saving disk 
space and making administration a whole bunch easier.

Once installed, adding new Elgg sites is a matter of clicking 
on a button and entering in some details.

## What can I do with it?

You can do everything that you can do with Elgg, but with the 
ability to create new networks on demand. This will for example 
let you:

* Set up your own version of Ning or Buddypress
* In your organisation or institution, easily set up Elgg 
  sites for each department.
* ... etc...


## Usage

The basic concept is that you set up one PRIVATE domain/site on your apache host
that has ```/multisite/``` as its docroot, then set up a PUBLIC wildcard domain which has ```/elgg/```
as its docroot.

If you want an example of how to set it up, take a look at the configuration of the vagrant build.

### Basic Setup

* Create a database and install ```multisite/schema/multisite_mysql.sql```
* Create a database user with
  * The ability to create databases
  * The ability to grant privileges on those databases
* Modify the ```$CONFIG->multisite->db_*``` configuration in elgg/elgg-config/settings.php with these database settings
* Configure a PRIVATE domain to use ```/multisite/``` as a docroot
* Configure a PUBLIC wildcard domain to use ```/elgg/``` docroot
* Make sure ```/data/``` is writable by your webserver
* Visit your private domain & set up your first user and user domains

### Console tool

You can also perform basic domain management from the console tool. Log on to your server and use:

```
./ms.php
```

To see a list of commands you can execute.

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

## Licence and Copyright 

Elgg Multisite is (C) 2010-18 Marcus Povey All Rights Reserved 
    <https://www.marcus-povey.co.uk>

Released under GPLv2 (See LICENCE.txt)

### Elgg Multisite includes

* Elgg, distributed under the GPL v2. http://elgg.org
* Bootstrap, distributed under the MIT licence. https://getbootstrap.com)
* jQuery, distributed under the MIT Licence. https://github.com/jquery/jquery
* ToroPHP, distributed under the MIT Licence. https://github.com/anandkunal/ToroPHP/
* Bonita, distributed under the Apache 2 Licence. https://github.com/benwerd/bonita
* H5f HTML5 Form shim, distributed under the MIT License. https://github.com/ryanseddon/H5F
* Portions of Symfony, which is distributed under the MIT license.
  * Console application. https://github.com/symfony/Console
