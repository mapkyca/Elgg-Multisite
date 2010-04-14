<?php

	admin_gatekeeper();
	action_gatekeeper();
	
	// Get the plugin
	$plugin = get_input('plugin');
	if (!is_array($plugin)) {
		$plugin = array($plugin);
	}
	
	foreach ($plugin as $p) {
		if (elggmulti_is_plugin_available($p)) {
			if (enable_plugin($p)) {
				system_message(sprintf(elgg_echo('admin:plugins:enable:yes'), $p));
			} else {
				register_error(sprintf(elgg_echo('admin:plugins:enable:no'), $p));
			}
		}
	}
	
	// Always enable plugin manager
	enable_plugin('pluginmanager');
	
	elgg_view_regenerate_simplecache();
	elgg_filepath_cache_reset();
	
	forward($_SERVER['HTTP_REFERER']);
?>