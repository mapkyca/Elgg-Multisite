<?php

namespace ElggMultisite\Pages\Session {

    class Delete extends \ElggMultisite\Page {

	public function get() {
	    $this->gatekeeper();
	    
	    if (\ElggMultisite\User::delete(\ElggMultisite\Input::getInput('user_id')))
		\ElggMultisite\Messages::addMessage ("User deleted");
	    
	    $this->forward('/users/');
	}

	public function post() {}

    }

}