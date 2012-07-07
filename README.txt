===============================================================
Multisite for Elgg
(C) 2010-12 Marcus Povey All Rights Reserved
Released under GPLv2 (See LICENCE.txt)
===============================================================

What is it?
===========

MP Multisite for Elgg allows you to run multiple separate Elgg 
sites off of the same install of the codebase, saving disk 
space and making administration a whole bunch easier.

Once installed, adding new Elgg sites is a matter of clicking 
on a button and entering in some details.

You are looking at the Elgg 1.7 Branch.

What can I do with it?
======================

You can do everything that you can do with Elgg, but with the 
ability to create new networks on demand. This will for example 
let you:

* Set up your own version of Ning!
* In your organisation or institution, easily set up Elgg 
  sites for each department.
* ... etc...

Building from Git
=================

Please note, you can not run elgg multisite straight from a git 
checkout. I.e. You can't just git clone the repo into a directory
and point your webserver at it.

To run from a git checkout you must build the project.

To help you do this the project includes an Apache Ant build file
which contains some handy installation options:

* ant test - builds and installs into 
	CURRENTUSER/~public_html/multisiteelgg_VERSION

* ant release - builds a release .zip file which you can use
	to install into another location.

Modify paths accordingly.


See elggmulti/README.txt for more...

