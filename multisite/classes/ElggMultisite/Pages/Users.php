<?php

namespace ElggMultisite\Pages {
    
    class Users extends \ElggMultisite\Page {
	
	public function get() {
	    $this->gatekeeper();
	    
	    $t = new \Bonita\Templates();

	    $t->title = 'Marcus Povey\'s Elgg Multisite: Users';

	    $t->body = $t->draw('pages/users');

	    $t->drawPage();
	}

	public function post() {
	    $this->gatekeeper();
	}

    }
}