<?php

namespace ElggMultisite {
    
    
    abstract class Page {
	
	abstract function get();
	abstract function post();
	
	
	public function forward($location) {
	    header('Location: ' . $location);
	    
	    exit;
	}
	
	public static function gatekeeper() {
	    
	    if (!User::isLoggedIn())
		$this->forward ('/');
	    
	    return true;
	}
    }
}