<?php

namespace ElggMultisite\Pages {
    
    class Users extends \ElggMultisite\Page {
	
	public function get() {
	    $this->gatekeeper();
	}

	public function post() {
	    $this->gatekeeper();
	}

    }
}