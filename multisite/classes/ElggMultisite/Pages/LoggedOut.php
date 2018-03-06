<?php

namespace ElggMultisite\Pages {

    class LoggedOut extends \ElggMultisite\Page {

	public function get() {
	    
	    if (\ElggMultisite\User::isLoggedIn())
		$this->forward('/domains/');

	    $t = new \Bonita\Templates();

	    $t->title = 'Marcus Povey\'s Elgg Multisite';

	    $t->body = $t->draw('pages/loggedout');

	    $t->drawPage();
	}

	public function post() {
	    
	}

    }

}