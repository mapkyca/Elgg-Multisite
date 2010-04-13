<?php

	global $CONFIG;
	
	$limit = get_input('limit', 10);
	$offset = get_input('offset', 0);
	
	// Get the installed plugins
	$installed_plugins = $vars['installed_plugins'];
	$count = count($installed_plugins);
	
	$plugin_list = get_plugin_list();
	$max = 0;
	foreach($plugin_list as $key => $foo) {
		if ($key > $max) $max = $key;
	}

	// Display list of plugins
	$n = 0;
	foreach ($installed_plugins as $plugin => $data) 
	{
		echo elgg_view("admin/plugins_opt/plugin", array('plugin' => $plugin, 'details' => $data, 'maxorder' => $max, 'order' => array_search($plugin, $plugin_list)));
	
		$n++;
	}
?>