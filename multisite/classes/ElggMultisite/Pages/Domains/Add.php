<?php

namespace ElggMultisite\Pages\Domains {
    
    class Add extends \ElggMultisite\Page {
	
	public function get() {
	    $this->gatekeeper();
	    
	}

	public function post() {
	    $this->gatekeeper();
	    
	    try {
		\ElggMultisite\Domain::addDomain(
			\ElggMultisite\Input::getInput('domain'), 
			\ElggMultisite\Input::getInput('dbsettings'), 
			\ElggMultisite\Input::getInput('available_plugins'), 
			\ElggMultisite\Input::getInput('class')
		);
	    } catch (\Exception $e) {
		\ElggMultisite\Messages::addMessage($e->getMessage());
	    }
	    
	    $this->forward('/domains/');
	}

    }
}