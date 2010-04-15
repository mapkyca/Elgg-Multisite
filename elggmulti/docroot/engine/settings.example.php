<?php
	/**
	 * Elgg multisite library.
	 *
	 * @package ElggMultisite
	 * @author Marcus Povey <marcus@marcus-povey.co.uk>
	 * @copyright Marcus Povey / UnofficialElgg.com 2010
	 * @link http://www.unofficialelgg.com/
	 * @link http://www.marcus-povey.co.uk/
 	 */

	global $CONFIG;
	
	if (!isset($CONFIG)) 
		$CONFIG = new stdClass;
		
	require_once(dirname(__FILE__) . '/lib/multisite.php');
	
	$CONFIG->multisite = new stdClass;
	
	/**
	 * Configure multisite support here, see
	 * ELGGMULTI.txt for details.
	 */
	$CONFIG->multisite->dbuser = 'your username';
	$CONFIG->multisite->dbpass = 'password';
	$CONFIG->multisite->dbhost = 'host';
	
	$CONFIG->multisite->dbname = 'elggmultisite';
	
	/**
	 * Detect the current domain and configure database accordingly.
	 * 
	 * Currently split databases are not supported.
	 */
	$db_settings = elggmulti_get_db_settings();
	$CONFIG->dataroot = $db_settings->dataroot;
	$CONFIG->dbuser = $db_settings->dbuser;
	$CONFIG->dbpass = $db_settings->dbpass;
	$CONFIG->dbname = $db_settings->dbname;
	$CONFIG->dbhost = $db_settings->dbhost;
	$CONFIG->dbprefix = $db_settings->dbprefix;

	/** Default plugins */
	$CONFIG->default_plugins = 'profile, river, logbrowser, uservalidationbyemail, htmlawed, search, pluginmanager';
	
	// URL
	$CONFIG->url = "";
?>