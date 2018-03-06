<?php

namespace ElggMultisite\Pages {
    
    class Domains extends \ElggMultisite\Page {
	
	public function get() {
	    $this->gatekeeper();
	    
	    $t = new \Bonita\Templates();

	    $t->title = 'Marcus Povey\'s Elgg Multisite: Domains';

	    $t->body = $t->draw('pages/domains');

	    $t->drawPage();
	}

	public function post() {
	    $this->gatekeeper();
	}

    }
}