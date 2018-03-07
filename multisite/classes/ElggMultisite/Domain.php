<?php

namespace ElggMultisite {

    class Domain implements
	\Iterator, // Override foreach behaviour 
	\ArrayAccess // Override for array access 
    {

	private $attributes = array();
	private $domain = "";
	private $id;

	public function __construct($url = '') {
	    if ($url) {
		if ($this->load($url) === false)
		    throw new Exception("Domain settings for $url could not be found");
	    }
	}

	public function __get($name) {
	    return $this->attributes[$name];
	}

	public function __set($name, $value) {
	    return $this->attributes[$name] = $value;
	}

	public function __isset($name) {
	    return isset($this->attributes[$name]);
	}

	public function __unset($name) {
	    unset($this->attributes[$name]);
	}

	// ITERATOR INTERFACE //////////////////////////////////////////////////////////////
	private $__iterator_valid = FALSE;

	public function rewind() {
	    $this->__iterator_valid = (FALSE !== reset($this->attributes));
	}

	public function current() {
	    return current($this->attributes);
	}

	public function key() {
	    return key($this->attributes);
	}

	public function next() {
	    $this->__iterator_valid = (FALSE !== next($this->attributes));
	}

	public function valid() {
	    return $this->__iterator_valid;
	}

	// ARRAY ACCESS INTERFACE //////////////////////////////////////////////////////////
	public function offsetSet($key, $value) {
	    if (array_key_exists($key, $this->attributes))
		$this->attributes[$key] = $value;
	}

	public function offsetGet($key) {
	    if (array_key_exists($key, $this->attributes))
		return $this->attributes[$key];
	}

	public function offsetUnset($key) {
	    if (array_key_exists($key, $this->attributes))
		$this->attributes[$key] = "";
	}

	public function offsetExists($offset) {
	    return array_key_exists($offset, $this->attributes);
	}

	/**
	 * Can the site be accessed? 
	 * 
	 * Override in order to check quotas etc.
	 *
	 * @return unknown
	 */
	public function isSiteAccessible() {
	    if ($this->enabled == 'no')
		return false;

	    return true;
	}

	public function setDomain($url) {
	    $this->domain = $url;
	}

	public function getDomain() {
	    return $this->domain;
	}

	public function getID() {
	    return $this->id;
	}

	public function isDbInstalled() {
	    
	    if (DB::execute("SHOW tables like ':prefix%'", [':prefix' => $this->dbprefix])) {
		return true;
	    }
	    
	    return false;
	}

	public function getDBVersion() {
	    
	    if ($result = DB::execute("SELECT * FROM {$this->dbprefix}datalists WHERE name='version'"))
		return $result->value;

	    return false;
	}

	public function disable_plugin($plugin) {
	    return $this->toggle_plugin($plugin, false);
	}

	public function enable_plugin($plugin) {
	    return $this->toggle_plugin($plugin, true);
	}
	
	protected function toggle_plugin($plugin, $enable = true, $site_id = 1) {
	    

	    $string = DB::execute("SELECT * FROM {$this->dbprefix}metastrings WHERE string='enabled_plugins'");
	    if (!$string) {
		
		$enabled_id = DB::insert("INSERT into {$this->dbprefix}metastrings (string) VALUES ('enabled_plugins')");
		
	    } else {

		$enabled_id = $string->id;
	    }

	    $result = DB::execute("SELECT * FROM {$this->dbprefix}metastrings WHERE string=:plugin", [':plugin' => $plugin]);
	    if (!$result)
		return false;
	    $string = $result[0];
	    if (!$string) {
		$plugin_id = DB::insert("INSERT into {$this->dbprefix}metastrings (string) VALUES (:plugin)", [':plugin' => $plugin]);
	    } else {
		$plugin_id = $string->id;
	    }

	    // Insert metadata
	    if ($enable) {
		/* 	$query = "INSERT INTO {$this->dbprefix}metadata (entity_guid, name_id, value_id, value_type, owner_guid, access_id, time_created) VALUES($site_id, $enabled_id, $plugin_id, 'text', 2, 2, '".time()."')";

		  mysql_query($query); */

		// TODO : Enable
	    } else {
		DB::execute("DELETE from {$this->dbprefix}metadata WHERE entity_guid=:site_id and name_id=:name_id and value_id=:value_id", [':site_id' => $site_id, ':name_id' => $enabled_id, ':value_id' => $plugin_id]);
	    }

	    return true;
	}
	
	/**
	 * Get plugins which have been activated for a given domain.
	 *
	 * @param int $domain_id
	 * @return array|false
	 */
	function getActivatedPlugins()
	{
	    $domain_id = $this->id;
	    
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
	 * Save object to database.
	 */
	public function save() {
	    $class = get_class($this);
	    $url = $this->getDomain();

	    if (!$this->id) {
		$this->id = DB::insert("INSERT into domains (domain, class) VALUES (:url, :class)", [':url' => $url, ':class' => $class]);
	    } else
		$result = DB::execute("UPDATE domains set domain=:url, class=:class WHERE id=:id", [':url' => $url, ':class' => $class, ':id' => $this->id]);

	    if (!$result)
		return false;

	    DB::execute("DELETE from domains_metadata where domain_id=:domain_id", [':domain_id' => $this->id]);

	    foreach ($this->attributes as $key => $value) {
		
		// Convert non-array to array 
		if (!is_array($value))
		    $value = array($value);

		// Save metadata
		foreach ($value as $meta) {
		    DB::insert("INSERT into domains_metadata (domain_id, name,value) VALUES (:domain_id, :name, :value)", [':domain_id' => $this->id, ':name' => $key, ':value' => $meta]);
		}
	    }
	}

	/**
	 * Load database settings.
	 *
	 * @param string $url URL to load
	 */
	public function load($url) {
	    $row = DB::execute("SELECT * from domains WHERE domain=:url LIMIT 1", [':url' => $url]);
	    if (!$row)
		return false;

	    $row = $row[0];
	    $this->domain = $row->domain;
	    $this->id = $row->id;

	    $meta = DB::execute("SELECT * from domains_metadata where domain_id = :domain_id", [':domain_id' => $row->id]);
	    if ($meta) {
		foreach ($meta as $md) {
		    $name = $md->name;
		    $value = $md->value;

		    // If already set, turn existing value into array
		    if ($this->$name) {
			$tmp = array($this->$name);
			$tmp[] = $value;
			$this->$name = $tmp;
		    } else
			$this->$name = $value;
		}
	    }
	}

	
	/**
	 * Return all domains
	 */
	public static function getDomains() {
	    if ($rows = DB::execute('SELECT * from domains')) {
		foreach ($rows as $key => $value) {
		    $rows[$key] = Router::toObj($value);
		}
		
		return $rows;
	    }
	}
	
	/**
	 * Return available multisite domains.
	 *
	 * @return array
	 */
	public static function getDomainTypes() {
	    return [
		'Domain' => 'Elgg domain',
	    ];
	}
	
	/**
	 * Return a list of all installed plugins.
	 *
	 */
	public static function getInstalledPlugins()
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

    }

}