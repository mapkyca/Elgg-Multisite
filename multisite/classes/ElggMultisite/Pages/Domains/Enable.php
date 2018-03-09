<?php

namespace ElggMultisite\Pages\Domains {

    class Enable extends \ElggMultisite\Page {

	public function post() {
	    $this->gatekeeper();
	}

	public function get() {
	    $this->gatekeeper();

	    $id = (int) \ElggMultisite\Input::getInput('domain_id');
	    
	    if (!empty($id)) {

		if ($domain = \ElggMultisite\Domain::getByID($id)) {

		    if ($domain->enable()) {
			\ElggMultisite\Messages::addMessage("Domain enabled");
		    }
		}
	    } else {
		\ElggMultisite\Messages::addMessage("No id given with domain");
	    }

	    $this->forward('/domains/');
	}

    }

}