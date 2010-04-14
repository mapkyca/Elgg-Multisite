<?php
	
	admin_gatekeeper();
	action_gatekeeper();
	
	// Get the plugin
	$plugin = get_input('plugin');
	if (!is_array($plugin)) {
		$plugin = array($plugin);
	}
	
	foreach ($plugin as $p) {
		// Disable
		if (($p != 'pluginmanager') && (disable_plugin($p))) {
			system_message(sprintf(elgg_echo('admin:plugins:disable:yes'), $p));
		} else {
			register_error(sprintf(elgg_echo('admin:plugins:disable:no'), $p));
		}
	}
	
	elgg_view_regenerate_simplecache();
	elgg_filepath_cache_reset();
	
	forward($_SERVER['HTTP_REFERER']);
?>