<?php

namespace ElggMultisite {

    class Site {

	private static $site;

	public function getDataDir() {
	    return dirname(dirname(dirname(dirname(__FILE__)))) . "/data/";
	}

	public function getWWWRoot() {
	    return '/'; // TODO: Do it better
	}

	public function checkInstall() {

	    if (!is_writable($this->getDataDir()))
		throw new \Exception ("It doesn't appear that your data directory (".$this->getDataDir ().") is writable.");
	    
	    try {
		if (!DB::db()) 
		    throw new \Exception("There was a problem connecting to the multisite directory.");
	    } catch (\Exception $ex) {
		throw new \Exception("There was a problem connecting to the multisite directory.");
	    }
	}
	
	
	/**
	 * Return a list of all installed plugins.
	 *
	 */
	public function getInstalledPlugins()
	{
	    $plugins = array();

	    $path = dirname(dirname(dirname(dirname(__FILE__)))) . '/elgg/mod/';

	    if ($handle = opendir($path)) {

		    while ($mod = readdir($handle)) {

			    if (!in_array($mod,array('.','..','.svn','CVS', '.git')) && is_dir($path . "/" . $mod)) {
				    if ($mod!='pluginmanager') // hide plugin manager
					    $plugins[] = $mod;
			    }

		    }
	    }

	    sort($plugins);

	    return $plugins;
	}
	
	/**
	 * Get plugins which have been activated for a given domain.
	 *
	 * @param int $domain_id
	 * @return array|false
	 */
	function getActivatedPlugins($domain_id = null)
	{
	    if (!$domain_id)
	    {
		$router = new Router();
		$result = $router->route();

		$domain_id = $result->getID();
	    }

	    $domain_id = (int)$domain_id;

	    $result = DB::execute("SELECT * from domains_activated_plugins where domain_id=:domain_id", [':domain_id' => $domain_id]);
	    $resultarray = array();
	    foreach ($result as $r)
		$resultarray[] = $r->plugin;

	    return $resultarray;
	}
	
	/**
	 * Activate or deactivate a plugin. 
	 *
	 * @param unknown_type $domain_id
	 * @param unknown_type $plugin
	 * @param unknown_type $activate
	 */
	function setActivatedPlugins($domain_id, $plugin, $activate = true)
	{
	    if ($activate)
	    {
		DB::insert("INSERT into domains_activated_plugins (domain_id, plugin) VALUES (:domain_id, :plugin)", [':domain_id' => $domain_id, ':plugin' => $plugin]);
	    }
	    else
	    {
		DB::delete("DELETE FROM domains_activated_plugins where domain_id=:domain_id and plugin=:plugin", [':domain_id' => $domain_id, ':plugin' => $plugin]);

		$domain = Domain::getByID($domain_id);
		if ($domain)
		    $domain->disable_plugin($plugin);
	    }
	}

	/**
	 * 
	 * @return \ElggMultisite\Site
	 */
	public static function site() {

	    if (empty(self::$site))
		self::$site = new Site();

	    return self::$site;
	}

    }

}