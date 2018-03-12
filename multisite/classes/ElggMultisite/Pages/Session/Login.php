<?php

namespace ElggMultisite\Pages\Session {
    
    class Login extends \ElggMultisite\Page {
	
	public function get() {
	    
	}

	public function post() {
	    
	    $this->tokenGatekeeper('/session/login');
	    
	    $username = \ElggMultisite\Input::getInput('username');
	    $password = \ElggMultisite\Input::getInput('password');
	    
	    if (\ElggMultisite\User::login($username, $password)) {
		
		error_log("User $username logged in");
	    }
	    
	    
	    
	    $this->forward('/');
	}

    }
}