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
	
	public function disable() {
	    $this->enabled = 'no';
	    
	    return $this->save();
	}
	
	public function enable() {
	    $this->enabled = 'yes';
	    
	    return $this->save();
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
	    
	    if (!empty($this->dbname)) {
		if ($result = DB::execute("SELECT * from {$this->dbname}.{$this->dbprefix}sites_entity")) {		    
		    return true;
		}
	    }
	    return false;
	}

	public function getDBVersion() {
	    
	    try {
		if ($result = DB::execute("SELECT * FROM {$this->dbname}.{$this->dbprefix}datalists WHERE name='version'"))
		    return $result[0]->value;
	    } catch (\Exception $e) {
		
	    }

	    return false;
	}

	public function disable_plugin($plugin) {
	    return $this->toggle_plugin($plugin, false);
	}

	public function enable_plugin($plugin) {
	    return $this->toggle_plugin($plugin, true);
	}
	
	protected function toggle_plugin($plugin, $enable = true, $site_id = 1) {
	    
	    $string = DB::execute("SELECT * FROM {$this->dbname}.{$this->dbprefix}metastrings WHERE string='enabled_plugins'");
	    if (!$string) {
		
		$enabled_id = DB::insert("INSERT into {$this->dbname}.{$this->dbprefix}metastrings (string) VALUES ('enabled_plugins')");
		
	    } else {

		$enabled_id = $string->id;
	    }

	    $result = DB::execute("SELECT * FROM {$this->dbname}.{$this->dbprefix}metastrings WHERE string=:plugin", [':plugin' => $plugin]);
	    if (!$result)
		return false;
	    $string = $result[0];
	    if (!$string) {
		$plugin_id = DB::insert("INSERT into {$this->dbname}.{$this->dbprefix}metastrings (string) VALUES (:plugin)", [':plugin' => $plugin]);
	    } else {
		$plugin_id = $string->id;
	    }

	    // Insert metadata
	    if ($enable) {
		/* 	$query = "INSERT INTO {$this->dbprefix}metadata (entity_guid, name_id, value_id, value_type, owner_guid, access_id, time_created) VALUES($site_id, $enabled_id, $plugin_id, 'text', 2, 2, '".time()."')";

		  mysql_query($query); */

		// TODO : Enable
	    } else {
		DB::execute("DELETE from {$this->dbname}.{$this->dbprefix}metadata WHERE entity_guid=:site_id and name_id=:name_id and value_id=:value_id", [':site_id' => $site_id, ':name_id' => $enabled_id, ':value_id' => $plugin_id]);
	    }

	    return true;
	}
	
	public function delete() {
	    
	    DB::delete("DELETE from domains where id = :id", [':id' => $this->id]);
	    DB::delete("DELETE from domains_metadata where domain_id=:domain_id", [':domain_id' => $this->id]);
	    
	    return true;
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
		$result = DB::update("UPDATE domains set domain=:url, class=:class WHERE id=:id", [':url' => $url, ':class' => $class, ':id' => $this->id]);


	    DB::delete("DELETE from domains_metadata where domain_id=:domain_id", [':domain_id' => $this->id]);

	    foreach ($this->attributes as $key => $value) {
		
		// Convert non-array to array 
		if (!is_array($value))
		    $value = array($value);

		// Save metadata
		foreach ($value as $meta) {
		    //error_log("Inserting $this->id - $key as $meta");
		    DB::insert("INSERT into domains_metadata (domain_id, name, value) VALUES (:domain_id, :name, :value)", [':domain_id' => $this->id, ':name' => $key, ':value' => $meta]);
		}
	    }
	    
	    return $this->id;
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
		    try {
			$rows[$key] = Router::toObj($value);
		    } catch (\Exception $e) {
			unset($rows[$key]);
		    }
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
	
	
	public static function addDomain($url, $dbsettings = [], $plugins = [], $class = "ElggMultisite\\Domain") {
	    
	    global $CONFIG;
	    
	    if (User::isLoggedIn()) {
		$domain = false;
			
		switch ($class)
		{
			case 'ElggMultisite\\Domain' :
			default:
			    $domain = new Domain();
		}
			
		if ($domain) {

		    // Common url settings
		    $domain->setDomain($url);

		    // Common db settings
		    $domain->dbname = $dbsettings['dbname'];
		    if (!$domain->dbname) {
			
			$url = preg_replace("/[^a-zA-Z0-9\s]/", "_", $url);
			$domain->dbname= $url;
			
		    }

		    $domain->dbuser = $dbsettings['dbuser'];
		    if (!$domain->dbuser)
			$domain->dbuser=$CONFIG->multisite->dbuser;
					
		    $domain->dbpass = $dbsettings['dbpass'];
		    if (!$domain->dbpass)
			$domain->dbpass=$CONFIG->multisite->dbpass;
					
		    $domain->dbhost = $dbsettings['dbhost'];
		    if (!$domain->dbhost)
			$domain->dbhost=$CONFIG->multisite->dbhost;
					
		    $domain->dbprefix = $dbsettings['dbprefix'];
		    if (!$domain->dbprefix)
			$domain->dbprefix = 'elgg';
				
		    $domain->dataroot = dirname(dirname(dirname(dirname(__FILE__)))) . "/data/$url/"; 
		    @mkdir($domain->dataroot, 0777);

		    $dbname = $domain->dbname;
		    $dbuser = $domain->dbuser;
		    $dbpass = $domain->dbpass;
		    $dbhost = $domain->dbhost;
					
		    if (!DB::create($dbname))
			throw new \Exception("Could not create database $dbname@$dbhost, check permissions and check that it doesn't already exist!");
					
		    if (!DB::grant("grant all privileges on `$dbname`.* to `$dbuser`@`$dbhost` identified by :dbpass", [':dbpass' => $dbpass]))
			throw new \Exception("Unable to grant access (all) to `$dbuser`@`$dbhost` on $dbname, please do this manually or you will likely have problems.");
		    
		    // Install elgg database
		    DB::source(
			dirname(dirname(dirname(dirname(__FILE__)))) . '/elgg/vendor/elgg/elgg/engine/schema/mysql.sql',
			$domain->dbprefix,
			$dbname
		    );
				
		    // Save
		    $domain_id = null;
		    if ($domain_id = $domain->save())
			Messages::addMessage('New domain created');
					
					
		    // Activate/deactivate plugins
		    $activated = Site::site()->getInstalledPlugins();
		    foreach ($activated as $plugin)
		    {
			if (in_array($plugin, $plugins)) {
			    Site::site()->setActivatedPlugins($domain_id, $plugin); // Active plugin globally
			} else {
			    Site::site()->setActivatedPlugins($domain_id, $plugin, false); // Deactivate plugin globally
			}
		    }
		    
		}
	    }
	    
	}
	
	/**
	 * Get a given domain by id
	 * @param type $id
	 * @return \ElggMultisite\Domain
	 */
	public static function getByID($id) {
	    if ($result = DB::execute("SELECT * from domains where id = :id", [':id' => $id])){
		return Router::toObj($result[0]);
	    }
	}

    }

}