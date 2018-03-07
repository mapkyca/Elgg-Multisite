<?php

namespace ElggMultisite {

    class DB {

	private static $db;

	public static function db() {

	    global $CONFIG;

	    if (empty(self::$db)) {

		$connection_string = 'mysql:host=' . $CONFIG->multisite->dbhost . ';dbname=' . $CONFIG->multisite->dbname . ';charset=utf8';

		self::$db = new \PDO($connection_string, $CONFIG->multisite->dbuser, $CONFIG->multisite->dbpass, array(\PDO::MYSQL_ATTR_LOCAL_INFILE => 1));
		self::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	    }
	    
	    return self::$db;
	}
	
	public static function execute($query, $values = []) {
	    
	    $statement = self::db()->prepare($query);
	    if ($statement->execute($values)) {
		if ($row = $statement->fetchAll(\PDO::FETCH_OBJ)) {
		    return $row;
		}
	    }

	    return false;
	}
	
	public static function insert($query, $values = []) {
	    
	    self::execute($query, $values);
	    
	    return self::db()->lastInsertId();
	}

    }

}