<?php

namespace ElggMultisite\Elgg {

    class MultisiteInstaller extends \ElggInstaller {

	/**
	 * Site settings controller
	 *
	 * Sets the site name, URL, data directory, etc.
	 *
	 * @param array $submissionVars Submitted vars
	 *
	 * @return void
	 */
	protected function settings($submissionVars) {

	    global $CONFIG;
	    
	    $formVars = array(
		'sitename' => array(
		    'type' => 'text',
		    'value' => 'My New Community',
		    'required' => TRUE,
		),
		'siteemail' => array(
		    'type' => 'email',
		    'value' => '',
		    'required' => FALSE,
		),
		'wwwroot' => array(
		    'type' => 'url',
		    'value' => _elgg_services()->config->getSiteUrl(),
		    'required' => TRUE,
		),
		'dataroot' => array(
		    'type' => 'text',
		    'value' => $CONFIG->dataroot,
		    'required' => TRUE,
		    'readonly' => true
		),
		'siteaccess' => array(
		    'type' => 'access',
		    'value' => ACCESS_PUBLIC,
		    'required' => TRUE,
		),
	    );

	    // if Apache, we give user option of having Elgg create data directory
	    //if (ElggRewriteTester::guessWebServer() == 'apache') {
	    //	$formVars['dataroot']['type'] = 'combo';
	    //	$GLOBALS['_ELGG']->translations['en']['install:settings:help:dataroot'] =
	    //			$GLOBALS['_ELGG']->translations['en']['install:settings:help:dataroot:apache'];
	    //}

	    if ($this->isAction) {
		do {
		    //if (!$this->createDataDirectory($submissionVars, $formVars)) {
		    //	break;
		    //}

		    if (!$this->validateSettingsVars($submissionVars, $formVars)) {
			break;
		    }

		    if (!$this->saveSiteSettings($submissionVars)) {
			break;
		    }

		    system_message(_elgg_services()->translator->translate('install:success:settings'));

		    $this->continueToNextStep('settings');
		} while (FALSE);  // PHP doesn't support breaking out of if statements
	    }

	    $formVars = $this->makeFormSticky($formVars, $submissionVars);

	    $this->render('settings', array('variables' => $formVars));
	}

    }

}