<?php

/**
 * Elgg multisite plugin manager.
 * 
 * Replaces the standard Elgg plugin manager with one linked to the accounts system.
 *
 * @package ElggMultisite
 * @subpackage PluginManager
 * @author Marcus Povey <marcus@marcus-povey.co.uk>
 * @copyright Marcus Povey 2018
 * @link https://www.marcus-povey.co.uk/
 */


require_once(dirname(__FILE__) . '/../../../multisite/vendor/bonita/start.php');

elgg_register_event_handler('init', 'system', function () {

    elgg_register_action('admin/plugins/activate', dirname(__FILE__) . '/actions/activate.php'); // Enable
    elgg_register_action('admin/plugins/deactivate', dirname(__FILE__) . '/actions/deactivate.php'); // Disable
    elgg_register_action('admin/plugins/activate_all', dirname(__FILE__) . '/actions/activate_all.php'); // Enable all
    elgg_register_action('admin/plugins/deactiveate_all', dirname(__FILE__) . '/actions/activate_all.php'); // Disable all

    elgg_register_action('admin/site/update_advanced', dirname(__FILE__) . 'actions/update_advanced.php'); // Disable all
    //register_action('admin/plugins/set_priority', false, $dirname(__FILE__). '/actions/reorder.php', true); // Reorder
}, 999);

/**
 * Hook into boot events, make sure settings are correctly preserved.
 * @global type $CONFIG 
 */
elgg_register_event_handler('plugins_boot', 'system', function () {

    global $CONFIG;

    $CONFIG->dataroot = $CONFIG->elgg_multisite_settings->dataroot;
}, 999);
