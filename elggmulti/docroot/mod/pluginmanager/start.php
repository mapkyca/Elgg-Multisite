<?php
	/**
	 * Elgg multisite plugin manager.
	 * 
	 * Replaces the standard Elgg plugin manager with one linked to the accounts system.
	 *
	 * @package ElggMultisite
	 * @subpackage PluginManager
	 * @author Marcus Povey <marcus@marcus-povey.co.uk>
	 * @copyright Marcus Povey 2010
	 * @link http://www.marcus-povey.co.uk/
 	 */


	function pluginmanager_init()
	{
		global $CONFIG;
		
		register_action('admin/plugins/activate', false, $CONFIG->pluginspath . 'pluginmanager/actions/enable.php', true); // Enable
		register_action('admin/plugins/deactivate', false, $CONFIG->pluginspath . 'pluginmanager/actions/disable.php', true); // Disable
		register_action('admin/plugins/activate_all', false, $CONFIG->pluginspath . 'pluginmanager/actions/enableall.php', true); // Enable all
		register_action('admin/plugins/deactiveate_all', false, $CONFIG->pluginspath . 'pluginmanager/actions/disableall.php', true); // Disable all
	
		//register_action('admin/plugins/set_priority', false, $CONFIG->pluginspath . 'pluginmanager/actions/reorder.php', true); // Reorder
	}
	
		
	register_elgg_event_handler('init','system','pluginmanager_init');
