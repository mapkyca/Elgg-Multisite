<?php

namespace ElggMultisite\Elgg {

    class DB {
	
	private $db;
	
	public function __construct($dbname) {
	    global $CONFIG;
	    
	    $connection_string = 'mysql:host=' . $CONFIG->multisite->dbhost . ';dbname=' . $dbname . ';charset=utf8';

	    $this->db = new \PDO($connection_string, $CONFIG->multisite->dbuser, $CONFIG->multisite->dbpass, array(\PDO::MYSQL_ATTR_LOCAL_INFILE => 1));
	    $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}
	
	public function execute($query, $values = []) {

	    $statement = $this->db->prepare($query);
	    if ($statement->execute($values)) {
		if ($row = $statement->fetchAll(\PDO::FETCH_OBJ)) {
		    return $row;
		}
	    }

	    return false;
	}
    }

}