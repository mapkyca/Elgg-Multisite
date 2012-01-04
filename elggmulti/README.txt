===============================================================
Multisite for Elgg
(C) 2010-12 Marcus Povey All Rights Reserved
Released under GPLv2 (See LICENCE.txt)
===============================================================

This is a version of Elgg which supports multiple sites on a 
single installation.

This is currently based on Elgg 1.7.

BACKGROUND
==========

By popular request, this is a migration from the old google 
code repo to git.

INSTALLATION
============

1) Unzip the package on your web server.

2) Point your master domain at the contents of the install 
   location on your web server (e.g /var/multisite).
   You will want to make sure that this location is 
   behind some form of access restriction limiting it to 
   only your organisation as it will contain the data 
   root of all elgg domains.

3) Point any sub domains to the contents of the docroot 
   folder, eg (/var/multisite/docroot)

4) Chmod 777 docroot/data - this is the default location for 
   multisite domains data directories.

5) Install schema/multisite_mysql.sql

6) Rename settings.example.php in docroot/elgg/engine/ to 
   settings.php and configure:
	
		$CONFIG->multisite->dbuser = 'your username';
		$CONFIG->multisite->dbpass = 'password';
		$CONFIG->multisite->dbhost = 'host';

   as appropriate.		
	
   It is recommended that this user have the privileges to 
   create databases and grant access to them.

7) Visit your master domain and configure your admin user

8) Begin configuring your sites! 


CONTACT
=======

Marcus Povey <marcus@marcus-povey.co.uk>
http://www.marcus-povey.co.uk


ACKNOWLEDGEMENTS
================

Elgg was created by Curverider and the open source community
and is copyrighted Curverider Ltd 2008-10

