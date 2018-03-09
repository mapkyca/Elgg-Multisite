<?php

namespace ElggMultisite\Pages\Domains {

    class Disable extends \ElggMultisite\Page {

	public function post() {
	    $this->gatekeeper();
	}

	public function get() {
	    $this->gatekeeper();

	    $id = (int) \ElggMultisite\Input::getInput('domain_id');
	    
	    if (!empty($id)) {

		if ($domain = \ElggMultisite\Domain::getByID($id)) {

		    if ($domain->disable()) {
			\ElggMultisite\Messages::addMessage("Domain disabled");
		    }
		}
	    } else {
		\ElggMultisite\Messages::addMessage("No id given with domain");
	    }

	    $this->forward('/domains/');
	}

    }

}