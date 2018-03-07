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

	public static function create($dbname) {

	    $statement = self::db()->prepare("create database if not exists $dbname");
	    if ($statement->execute([])) {
		return true;
	    }

	    return false;
	}

	public function grant($query, $values = []) {
	    $statement = self::db()->prepare($query);
	    if ($statement->execute($values)) {
		return true;
	    }

	    return false;
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
	
	
	public static function delete($query, $values = []) {

	    $statement = self::db()->prepare($query);
	    if ($statement->execute($values)) {
		return true;
	    }

	    return false;
	}

	public static function insert($query, $values = []) {

	    $statement = self::db()->prepare($query);
	    if ($statement->execute($values)) {
		return self::db()->lastInsertId();
	    }

	    return false;
	}

	public static function source($script, $prefix, $dbname) {
	    global $CONFIG;
	    
	    $connection_string = 'mysql:host=' . $CONFIG->multisite->dbhost . ';dbname=' . $dbname . ';charset=utf8';

	    $db = new \PDO($connection_string, $CONFIG->multisite->dbuser, $CONFIG->multisite->dbpass, array(\PDO::MYSQL_ATTR_LOCAL_INFILE => 1));
	    $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	    
	    $script = file_get_contents($script);
	    if ($script) {

		$errors = array();

		$script = preg_replace('/^(?:--|#) .*$/m', '', $script);
		$sql_statements = preg_split('/;[\n\r]+/', "$script\n");

		foreach ($sql_statements as $statement) {
		    $statement = trim($statement);
		    $statement = str_replace("prefix_", $prefix, $statement);
		    if (!empty($statement)) {
			//try {
			    $s = $db->prepare($statement);
			    $s->execute([]);
//			} catch (\Exception $e) {
//			    $errors[] = $e->getMessage();
//			}
		    }
		}
	    } else {
		throw new \Exception("Couldn't install route database");
	    }
	}

    }

}