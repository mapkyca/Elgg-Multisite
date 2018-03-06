<?php

namespace ElggMultisite\Pages {
    
    class Domains extends \ElggMultisite\Page {
	
	public function get() {
	    $this->gatekeeper();
	}

	public function post() {
	    $this->gatekeeper();
	}

    }
}