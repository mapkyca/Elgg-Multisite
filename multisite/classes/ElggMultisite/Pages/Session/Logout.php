<?php

namespace ElggMultisite\Pages\Session {
    
    class Logout extends \ElggMultisite\Page {
	
	public function get() {
	    
	    \ElggMultisite\User::logout();
	    
	    $this->forward('/');
	}

	public function post() {
	    
	}

    }
}