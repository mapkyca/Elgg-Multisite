<?php

namespace ElggMultisite {

    class Domain implements
	Iterator, // Override foreach behaviour 
	ArrayAccess // Override for array access 
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
		
		
		
		
		
		mysql_query("INSERT into {$this->dbprefix}metastrings (string) VALUES ('enabled_plugins')");
		$enabled_id = mysql_insert_id($link);
		
		
		
		
	    } else {

		$enabled_id = $string->id;
	    }

	    $result = mysql_query("SELECT * FROM {$this->dbprefix}metastrings WHERE string='$plugin'", $link);
	    if (!$result)
		return false;
	    $string = mysql_fetch_object($result);
	    if (!$string) {
		mysql_query("INSERT into {$this->dbprefix}metastrings (string) VALUES ('$plugin')");
		$plugin_id = mysql_insert_id($link);
	    } else {

		$plugin_id = $string->id;
	    }

	    // Insert metadata
	    if ($enable) {
		/* 	$query = "INSERT INTO {$this->dbprefix}metadata (entity_guid, name_id, value_id, value_type, owner_guid, access_id, time_created) VALUES($site_id, $enabled_id, $plugin_id, 'text', 2, 2, '".time()."')";

		  mysql_query($query); */

		// TODO : Enable
	    } else {
		$query = "DELETE from {$this->dbprefix}metadata WHERE entity_guid=$site_id and name_id=$enabled_id and value_id=$plugin_id";

		mysql_query($query);
	    }

	    return true;
	}

	/**
	 * Save object to database.
	 */
	public function save() {
	    $class = get_class($this);
	    $url = mysql_real_escape_string($this->getDomain());

	    $dblink = elggmulti_db_connect();
	    if (!$this->id) {
		$result = elggmulti_execute_query("INSERT into domains (domain, class) VALUES ('$url', '$class')");
		$this->id = mysql_insert_id($dblink);
	    } else
		$result = elggmulti_execute_query("UPDATE domains set domain='$url', class='$class' WHERE id={$this->id}");

	    if (!$result)
		return false;


	    elggmulti_execute_query("DELETE from domains_metadata where domain_id='{$this->id}'");

	    foreach ($this->attributes as $key => $value) {
		// Sanitise string
		$key = mysql_real_escape_string($key);

		// Convert non-array to array 
		if (!is_array($value))
		    $value = array($value);

		// Save metadata
		foreach ($value as $meta)
		    elggmulti_execute_query("INSERT into domains_metadata (domain_id,name,value) VALUES ({$this->id}, '$key', '" . mysql_real_escape_string($meta) . "')");
	    }
	}

	/**
	 * Load database settings.
	 *
	 * @param string $url URL to load
	 */
	public function load($url) {
	    $row = elggmulti_getdata_row("SELECT * from domains WHERE domain='$url' LIMIT 1");
	    if (!$row)
		return false;

	    $this->domain = $row->domain;
	    $this->id = $row->id;

	    $meta = elggmulti_getdata("SELECT * from domains_metadata where domain_id = {$row->id}");
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
	 * Return available multisite domains.
	 *
	 * @return array
	 */
	public static function getDomainTypes() {
	    return array(
		'MultisiteDomain' => 'Elgg domain',
	    );
	}

    }

}