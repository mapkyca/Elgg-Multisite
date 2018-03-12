<?php

namespace ElggMultisite {
    
    
    abstract class Page {
	
	abstract function get();
	abstract function post();
	
	
	public function forward($location) {
	    header('Location: ' . $location);
	    
	    exit;
	}
	
	public function gatekeeper() {
	    
	    if (!User::isLoggedIn())
		$this->forward ('/');
	    
	    return true;
	}
	
	public function tokenGatekeeper($action) {
	    \Bonita\Forms::validateToken($action, true);
	}
    }
}