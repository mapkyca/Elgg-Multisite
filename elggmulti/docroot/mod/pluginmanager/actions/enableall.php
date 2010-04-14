<?php
	admin_gatekeeper();
	action_gatekeeper();
	
	$plugins = get_installed_plugins();
	
	foreach ($plugins as $p => $data) {
		// Enable
		if (elggmulti_is_plugin_available($p)) {
			if ($p != 'pluginmanager')
			{
				if (enable_plugin($p)) {
					system_message(sprintf(elgg_echo('admin:plugins:enable:yes'), $p));
				} else {
					register_error(sprintf(elgg_echo('admin:plugins:enable:no'), $p));
				}
			}
		}
	}
	
	// Always enable plugin manager
	enable_plugin('pluginmanager');
	
	// Regen view cache
	elgg_view_regenerate_simplecache();
	elgg_filepath_cache_reset();
	
	forward($_SERVER['HTTP_REFERER']);
?>