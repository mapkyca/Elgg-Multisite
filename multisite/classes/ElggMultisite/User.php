<?php

namespace ElggMultisite {

    class User {

	
	
	public static function countUsers() {
	    
	    if ($row = DB::execute("SELECT count(username) as count from users"))
		    return $row->count;
	    return false;
	}
	
	public static function register($username, $password, $password2) {
	    if (strcmp($password, $password2) != 0)
		throw new \Exception('Passwords are not the same');

	    if (strlen($password) < 5)
		throw new \Exception('Passwords are too short');

	    if (strlen($username) < 5)
		throw new \Exception('Username is too short');

	    $salt = substr(md5(rand()), 0, 8);
	    $password = hash_hmac('md5', $password, $salt);

	    if ($result = \ElggMultisite\DB::insert('INSERT into users (username, password, salt) VALUES (:username, :password, :salt)', [':username' => $username, ':password' => $password, ':salt' => $salt])) {
		return $result;
	    }

	    return false;
	}

	public static function login($username, $password) {
	    $username = trim ($username);
	    $password = trim($password);
	    
	    if ($result = DB::execute("SELECT * from users where username = :username", [':username' => $username] )) {
		
		if (hash_hmac('md5', $password, $result->salt) == $result->password) {
		    
		    $_SESSION['em_user'] = $result;
		    session_regenerate_id();
		}
	    }
	    
	    return false;
	}

	public static function logout() {
	    $_SESSION['em_user'] = null;
	    session_destroy();
	}
	
	
	public static function isLoggedIn() {
	    
	    if (!empty($_SESSION['em_user'])) {
		return true;
	    }
	}
	
    }

}