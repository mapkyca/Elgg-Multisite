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
		
		public function setDomain($url) { $this->domain = $url; }
		public function getDomain() {return $this->domain; }
		public function getID() {return $this->id; }
		
		public function isDbInstalled()
		{
			$link = mysql_connect($this->dbhost, $this->dbuser, $this->dbpass, true);
			mysql_select_db($this->dbname, $link);
			
			$result = mysql_query("SHOW tables like '{$this->dbprefix}%'", $link);
			$result = mysql_fetch_object($result);
			if ($result)
				return true;
				
			return false;
		}
		
		public function getDBVersion()
		{
			$link = mysql_connect($this->dbhost, $this->dbuser, $this->dbpass, true);
			mysql_select_db($this->dbname, $link);
			
			$result = mysql_fetch_object(mysql_query("SELECT * FROM {$this->dbprefix}datalists WHERE name='version'"));
		
			if ($result)
				(int)$result->value;
				
			return false;
		}
 		
		/**
		 * Save object to database.
		 */
 		public function save()
 		{
 			$class = get_class($this);
 			$url = mysql_real_escape_string($this->getDomain());
 			
 			$dblink = elggmulti_db_connect();
 			if (!$this->id) {
 				$result = elggmulti_execute_query("INSERT into domains (domain, class) VALUES ('$url', '$class')");
 				$this->id = mysql_insert_id($dblink);
 			}
 			else
 				$result = elggmulti_execute_query("UPDATE domains set domain='$url', class='$class' WHERE id={$this->id}");
 		
 			if (!$result)
 				return false;
 				
 			
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
 				
 			$meta = elggmulti_getdata("SELECT * from domains_metadata where domain_id = {$row->id}");
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
 		
 		
 		/**
		 * Return available multisite domains.
		 *
		 * @return array
		 */
		public static function getDomainTypes() 
		{
			return array (
				'MultisiteDomain' => 'Default Elgg domain',
			);
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
		$result = elggmulti_getdata($query, $callback);
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
			if (strcmp($user->password, md5($password.$user->salt)) == 0) {

				$_SESSION['user'] = $user;
				
				session_regenerate_id();
			
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Log the current user out.
	 *
	 */
	function elggmulti_logout()
	{
		unset ($_SESSION['user']); 
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

		$salt = substr(md5(rand()), 0, 8);
		$username = mysql_real_escape_string(strtolower($username));
		$password = md5(mysql_real_escape_string($password) . $salt);
		
		$result = elggmulti_execute_query("INSERT into users (username,password,salt) VALUES ('$username', '$password', '$salt')");	
		$userid = mysql_insert_id($dblink);		
			
		return $userid;
	}
	
	/**
	 * Set user password.
	 *
	 * @param string $username
	 * @param string $password
	 * @param string $password2
	 * @return bool
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
	 * Count number of users.
	 *
	 */
	function elggmulti_countusers()
	{
		$row = elggmulti_getdata_row('SELECT count(*) as count FROM users');
		
		return $row->count;
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
	
	function elggmulti_get_messages()
	{
		if ((isset($_SESSION['_EM_MESSAGES'])) && (is_array($_SESSION['_EM_MESSAGES']))) 
		{
			$messages = $_SESSION['_EM_MESSAGES'];
			$_SESSION['_EM_MESSAGES'] = null;
			
			return $messages;
		}
		
		return false;
	}
	
	function elggmulti_set_message($message)
	{
		if (!is_array($_SESSION['_EM_MESSAGES']))
			$_SESSION['_EM_MESSAGES'] = array();
			
		$_SESSION['_EM_MESSAGES'][] = $message;
		
		return true;
	}
	
?>