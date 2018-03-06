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

* Set up your own version of Ning!
* In your organisation or institution, easily set up Elgg 
  sites for each department.
* ... etc...


## Usage

The basic concept is that you set up one PRIVATE domain/site on your apache host
that has ```/multisite/``` as its docroot, then set up a PUBLIC wildcard domain which has ```/elgg/```
as its docroot.

If you want an example of how to set it up, take a look at the configuration of the vagrant build.

## Vagrant build

If you want to start playing with this quickly, you can run the vagrant build.

* Install vagrant
* Modify your hosts
  * Set "elgg-multisite" to point to 192.168.33.35
  * Set "whatever.multi", "whatever2.multi", etc...
* ```vagrant up```
* Go to http://elgg-multisite and set up your first admin user
  * Log in admin
  * Create your whatever.multi domain
* Visit http://whatever.multi

## Copyright 

(C) 2010-18 Marcus Povey All Rights Reserved 
    <https://www.marcus-povey.co.uk>

## Licence

Released under GPLv2 (See LICENCE.txt)
