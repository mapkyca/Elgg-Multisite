<?php

namespace ElggMultisite\Pages\Session {

    class Register extends \ElggMultisite\Page {

	public function get() {
	    
	}

	public function post() {

	    $username = trim(\ElggMultisite\Input::getInput('username'));
	    $password = trim(\ElggMultisite\Input::getInput('password'));
	    $password2 = trim(\ElggMultisite\Input::getInput('password2'));

	    try {
		if (\ElggMultisite\User::register($username, $password, $password2))
		    $this->forward('/');

	    } catch (\Exception $e) {
		
	    }
	    throw new \Exception('Sorry, that user could not be registered');
	}

    }

}