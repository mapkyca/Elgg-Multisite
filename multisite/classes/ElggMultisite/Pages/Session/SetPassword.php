<?php

namespace ElggMultisite\Pages\Session {

    class SetPassword extends \ElggMultisite\Page {

	public function get() {
	    
	}

	public function post() {
	    $this->gatekeeper();

	    $username = trim(\ElggMultisite\Input::getInput('username'));
	    $password = trim(\ElggMultisite\Input::getInput('password'));
	    $password2 = trim(\ElggMultisite\Input::getInput('password2'));

	    try {
		if (\ElggMultisite\User::setPassword($username, $password, $password2)) {
		    \ElggMultisite\Messages::addMessage("Passwords changed!");
		    $this->forward('/users/');
		}

	    } catch (\Exception $e) {
		\ElggMultisite\Messages::addMessage($e->getMessage());
	    }
	    
	    \ElggMultisite\Messages::addMessage('Sorry, could not set the password');
	    $this->forward('/users/');
	}

    }

}