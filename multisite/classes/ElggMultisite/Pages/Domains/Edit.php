<?php

namespace ElggMultisite\Pages\Domains {

    class Edit extends \ElggMultisite\Page {

	public function get() {
	    $this->gatekeeper();
	}

	public function post() {
	    $this->gatekeeper();

	    $id = (int) \ElggMultisite\Input::getInput('id');
	    $url = \ElggMultisite\Input::getInput('domain');

	    if (!empty($id)) {

		if ($domain = \ElggMultisite\Domain::getByID($id)) {

		    $domain->setDomain($url);

		    if ($domain->save())
			\ElggMultisite\Messages::addMessage('Domain details updated');

		    // Don't set DB settings anymore
		    // Activate/deactivate plugins
		    $plugins = \ElggMultisite\Site::site()->getInstalledPlugins();
		    foreach ($plugins as $plugin) {
			if (in_array($plugin, \ElggMultisite\Input::getInput('available_plugins'))) {
			    \ElggMultisite\Site::site()->setActivatedPlugins($id, $plugin); // Active plugin globally
			} else {
			    \ElggMultisite\Site::site()->setActivatedPlugins($id, $plugin, false); // Deactivate plugin globally
			}
		    }
		}
	    } else {
		\ElggMultisite\Messages::addMessage("No id given with domain");
	    }

	    $this->forward('/domains/');
	}

    }

}