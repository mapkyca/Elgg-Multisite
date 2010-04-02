<?php
	/**
	 * Elgg multisite library.
	 *
	 * @package ElggMultisite
	 * @author Marcus Povey <marcus@marcus-povey.co.uk>
	 * @copyright Marcus Povey / UnofficialElgg.com 2010
	 * @link http://www.unofficialelgg.com/
	 * @link http://www.marcus-povey.co.uk/
 	 */

	/**
	 * Multisite domain.
	 */
	class MultisiteDomain implements
		Iterator,	// Override foreach behaviour
		ArrayAccess // Override for array access
	{
		private $__attributes = array();
		
		private $domain = "";
		private $id;
		
		public function __construct($url = '') 
		{
			if ($url) {
				if (!$this->load($url))
					throw new Exception("Domain settings for $url could not be found");
			}
		}
		
		public function __get($name) { return $this->__attributes[$name]; }
		public function __set($name, $value) { return $this->__attributes[$name] = $value; }
		public function __isset($name) { return isset($this->__attributes[$name]); }
		public function __unset($name) { unset($this->__attributes[$name]); }
		
		// ITERATOR INTERFACE //////////////////////////////////////////////////////////////
		private $__iterator_valid = FALSE; 
		
   		public function rewind() { $this->__iterator_valid = (FALSE !== reset($this->__attributes));  	}
   		public function current() 	{ return current($this->__attributes); 	}	
   		public function key() 	{ return key($this->__attributes); 	}	
   		public function next() { $this->__iterator_valid = (FALSE !== next($this->__attributes));  }
   		public function valid() { 	return $this->__iterator_valid;  }
   		
   		// ARRAY ACCESS INTERFACE //////////////////////////////////////////////////////////
		public function offsetSet($key, $value) { if ( array_key_exists($key, $this->__attributes) ) $this->__attributes[$key] = $value; } 
 		public function offsetGet($key) { if ( array_key_exists($key, $this->__attributes) ) return $this->__attributes[$key]; } 
 		public function offsetUnset($key) { if ( array_key_exists($key, $this->__attributes) ) $this->__attributes[$key] = ""; } 
 		public function offsetExists($offset) { return array_key_exists($offset, $this->__attributes);	} 
		
	
 		/**
 		 * Can the site be accessed? 
 		 * 
 		 * Override in order to check quotas etc.
 		 *
 		 * @return unknown
 		 */
		public function isSiteAccessible() { return true; }
		
		public function setDomain($url) { $this->url = $url; }
		public function getDomain() {return $this->url; }
 		
		/**
		 * Save object to database.
		 */
 		public function save()
 		{
 			$class = get_class($this);
 			$url = mysql_real_escape_string($this->getDomain());
 			
 			$dblink = elggmulti_db_connect();
 			$result = elggmulti_execute_query("INSERT into domains (domain, class) VALUES ('$url', '$class')");
 			if (!$result)
 				return false;
 				
 			$this->id = mysql_insert_id($dblink);
 				
 			elggmulti_execute_query("DELETE from domains_metadata where domain_id='{$this->id}'");
 			
 			foreach ($this->__attributes as $key => $value)
 			{
 				// Sanitise string
				$key = mysql_real_escape_string($key);
					
				// Convert non-array to array 
				if (!is_array($value))
					$value = array($value);
					
				// Save metadata
				foreach ($value as $meta)
					elggmulti_execute_query("INSERT into domains_metadata (domain_id,name,value) VALUES ({$this->id}, '$key', '".mysql_real_escape_string($meta)."')");
				
 			}
 		}
 		
 		/**
 		 * Load database settings.
 		 *
 		 * @param string $url URL to load
 		 */
 		public function load($url)
 		{
 			$row = elggmulti_getdata_row("SELECT * from domains WHERE domain='$url' LIMIT 1");
 			if (!$row)
 				return false;
 				
 			$this->domain = $row->domain;
 			$this->id = $row->id;
 				
 			$meta = elggmulti_getdata("SELECT * from domains_meta where domain_id = {$row->id}");
 			if ($meta)
 			{
 				foreach ($meta as $md)
 				{
 					$name = $md->name;
					$value = $md->value;
				
					// If already set, turn existing value into array
					if ($this->$name) 
					{
						$tmp = array($this->$name);
						$tmp[] = $value;
						$this->$name = $tmp;
					}	
					else
						$this->$name = $value;
 				}
 			}
 		}
 		
	}
	
	/**
	 * Helper function for constructing classes.
	 */
	function __elggmulti_db_row($row)
	{
		// Sanity check
		if (
			(!($row instanceof stdClass)) ||
			(!$row->id) ||
			(!$row->class)
		)	
			throw new Exception('Invalid handling class');  
			
		$class = $row->class;
		
		if (class_exists($class))
		{
			$object = new $class();
			
			if (!($object instanceof MultisiteDomain))
				throw new Exception('Class is invalid');
				
			$object->load($row->domain);
			
			return $object;
		}  
			
		return false;
	}

	/**
	 * Connect multisite database.
	 *
	 * @return dblink|false
	 */
	function elggmulti_db_connect()
	{
		global $CONFIG;
		
		if (!$CONFIG->elggmulti_link)
		{ 
			$CONFIG->elggmulti_link = mysql_connect($CONFIG->multisite->dbhost, $CONFIG->multisite->dbuser, $CONFIG->multisite->dbpass, true);
			mysql_select_db($CONFIG->multisite->dbname, $CONFIG->elggmulti_link);
		}
		
		if ($CONFIG->elggmulti_link)
			return $CONFIG->elggmulti_link;
			
		return false;
	}
	
	/**
	 * Execute a raw query.
	 *
	 * @param string $query
	 * @return dbresult|false
	 */
	function elggmulti_execute_query($query)
	{
		global $CONFIG;
		
		$dblink = elggmulti_db_connect();
		$result = mysql_query($query, $dblink);
		
		if ($result)
			return $result;
			
		return false;
	}
	
	/**
	 * Get data from a database.
	 *
	 * @param string $query
	 * @param string $callback
	 */
	function elggmulti_getdata($query, $callback = '')
	{
		$resultarray = array();
		
		$dblink = elggmulti_db_connect();
		
		if ($result = elggmulti_execute_query($query))
		{
			while ($row = mysql_fetch_object($result)) {
				if (!empty($callback) && is_callable($callback)) {
					$row = $callback($row);
				}
				if ($row) 
					$resultarray[] = $row;
			}
		}
		
		return $resultarray;
	}

	/**
	 * Get data row.
	 *
	 * @param string $query
	 */
	function elggmulti_getdata_row($query, $callback = '')
	{
		$result = elggmulti_getdata($query);
		if ($result)
			return $result[0];
			
		return false;
	}
	
	/**
	 * User login.
	 *
	 * @param string $username Username to retrieve
	 * @param string $password Password
	 * @return bool
	 */
	function elggmulti_login($username, $password) 
	{
		$username = mysql_real_escape_string($username);
		$user = elggmulti_getdata_row("SELECT * FROM users WHERE username='$username' LIMIT 1");
		
		if ($user)
		{
			if (strcmp($user->password, md5($username.$user->salt)) == 0)
				return true;
		}
		
		return false;
	}
	
	/**
	 * Create a new user item.
	 *
	 * @param string $username
	 * @param string $password
	 * @param string $password2
	 */
	function elggmulti_create_user($username, $password, $password2)
	{
		if (strcmp($password, $password2)!=0)
			return false;
			
		if (strlen($password) < 5)
			return false;
			
		if (strlen($username) < 5)
			return false;	
			
		$dblink = elggmulti_db_connect();

		$username = mysql_real_escape_string($username);
		$password = mysql_real_escape_string($password);
		$salt = substr(md5(rand(), 0, 8));
		
		$result = elggmulti_execute_query("INSERT into users (username,password,salt) VALUES ('$username', '$password', '$salt')");	
		$userid = mysql_insert_id($dblink);		
			
		return $userid;
	}
	
	/**
	 * Set user password.
	 *
	 * @param unknown_type $username
	 * @param unknown_type $password
	 * @param unknown_type $password2
	 * @return unknown
	 */
	function elggmulti_set_user_password($username, $password, $password2)
	{
		if (strcmp($password, $password2)!=0)
			return false;
			
		if (strlen($password) < 5)
			return false;
			
		$dblink = elggmulti_db_connect();

		$username = mysql_real_escape_string($username);
		$password = mysql_real_escape_string($password);
		$salt = substr(md5(rand(), 0, 8));

		return elggmulti_execute_query("UPDATE users SET password='$password', salt='$salt' WHERE username='$username'");
	}
	
	/**
	 * Retrieve db settings.
	 *
	 * Retrieve a database setting based on the current multisite domain
	 * detected.
	 * 
	 * @param $url The url who's settings you need to retrieve, detected if not provided.
	 * @return MultisiteDomain|false
	 */
	function elggmulti_get_db_settings($url = '')
	{
		global $CONFIG;
		
		$dblink = elggmulti_db_connect();
		$url = mysql_real_escape_string($url);
		
		// If no url then use the server referrer
		if (!$url) 
			$url = $_SERVER['SERVER_NAME'];

		$result = elggmulti_getdata_row("SELECT * from domains WHERE domain='$url' LIMIT 1", '__elggmulti_db_row');
		if ($result)
			return $result;
		
		return false;
	}
	
	/**
	 * Register a new collection of database settings. 
	 * 
	 * @param $dbhost The host
	 * @param $dbuser The user
	 * @param $dbpass The password
	 * @param $db The database
	 * @param $dbprefix The prefix
	 * @param $url Optional url to bind to
	 * @param $linktype The type of link (lets you separate reads from writes)
	 */
	function elggmulti_add_database_details($dbhost, $dbuser, $dbpass, $dbname, $dbprefix, $url, $linktype = "readwrite")
	{
	/*	global $DB_SETTINGS;

		if (!isset($DB_SETTINGS[$linktype]))
			$DB_SETTINGS[$linktype] = array();

		if (!isset($DB_SETTINGS[$linktype][$url]))
			$DB_SETTINGS[$linktype][$url] = array();

		$DB_SETTINGS[$linktype][$url]['dbhost'] = $dbhost;
		$DB_SETTINGS[$linktype][$url]['dbuser'] = $dbuser;
		$DB_SETTINGS[$linktype][$url]['dbpass'] = $dbpass;
		$DB_SETTINGS[$linktype][$url]['dbprefix'] = $dbprefix;
		$DB_SETTINGS[$linktype][$url]['dbname'] = $dbname;

		return true;*/
		
		
		
	}
?>