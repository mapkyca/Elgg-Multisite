<?php

/**
 * Elgg multisite library.
 *
 * @package ElggMultisite
 * @author Marcus Povey <marcus@marcus-povey.co.uk>
 * @copyright Marcus Povey 2018
 * @link http://www.marcus-povey.co.uk/
 */
global $CONFIG;

if (!isset($CONFIG))
    $CONFIG = new stdClass;

spl_autoload_register(function($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    $basedir = dirname(dirname(dirname(__FILE__))) . '/multisite/classes/';
    if (file_exists($basedir . $class . '.php')) {
	include_once($basedir . $class . '.php');
    }
});

$CONFIG->multisite = new stdClass;

/**
 * Configure multisite support here, see
 * README.md for details.
 */
$CONFIG->multisite->dbuser = 'elggmultisite';
$CONFIG->multisite->dbpass = 'elggmultisite';
$CONFIG->multisite->dbhost = 'localhost';

$CONFIG->multisite->dbname = 'elggmultisite';

/**
 * Detect the current domain and configure database accordingly.
 * 
 * Currently split databases are not supported.
 */
$router = new ElggMultisite\Router();
if ($db_settings = $router->route()) {

    $CONFIG->elgg_multisite_settings = $db_settings; // Make multisite settings available to peeps.

    $CONFIG->dataroot = $db_settings->dataroot;
    $CONFIG->dbuser = $db_settings->dbuser;
    $CONFIG->dbpass = $db_settings->dbpass;
    $CONFIG->dbname = $db_settings->dbname;
    $CONFIG->dbhost = $db_settings->dbhost;
    $CONFIG->dbprefix = $db_settings->dbprefix;
    
}

