<?php
	admin_gatekeeper();
	action_gatekeeper();
	
	// Get the plugin
	$mod = get_input('plugin');
	$mod = str_replace('.','',$mod);
	$mod = str_replace('/','',$mod);
	
	// Get the new order
	$order = (int) get_input('order');
	
	// Get the current plugin list
	$plugins = get_plugin_list();
	
	// Remove any not permitted 
	foreach ($plugins as $key => $value)
	{
		if (!elggmulti_is_plugin_available($value))
			unset($plugins[$key]);
			
		if ($value == 'pluginmanager')
			unset($plugins[$key]);
	}
	
	
	// Inject the plugin order back into the list
	if ($key = array_search($mod, $plugins)) {
	
		unset($plugins[$key]);
		while (isset($plugins[$order])) {
			$order++;
		}
	
		$plugins[$order] = $mod;
	}
	
	$plugins[] = 'pluginmanager';
	
	// Disable
	if (regenerate_plugin_list($plugins)) {
		system_message(sprintf(elgg_echo('admin:plugins:reorder:yes'), $plugin));
	} else {
		register_error(sprintf(elgg_echo('admin:plugins:reorder:no'), $plugin));
	}
	
	
	elgg_view_regenerate_simplecache();
	elgg_filepath_cache_reset();
	
	forward($_SERVER['HTTP_REFERER']);
?>