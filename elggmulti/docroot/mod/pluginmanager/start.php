<?php
	/**
	 * Elgg multisite plugin manager.
	 * 
	 * Replaces the standard Elgg plugin manager with one linked to the accounts system.
	 *
	 * @package ElggMultisite
	 * @subpackage PluginManager
	 * @author Marcus Povey <marcus@marcus-povey.co.uk>
	 * @copyright Marcus Povey / UnofficialElgg.com 2010
	 * @link http://www.unofficialelgg.com/
	 * @link http://www.marcus-povey.co.uk/
 	 */


	function pluginmanager_init()
	{
		
	}
	
		
	register_elgg_event_handler('init','system','pluginmanager_init');

?>