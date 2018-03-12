#!/usr/bin/php -q
<?php

define('ELGG-MULTISITE-CONSOLE', true);
require_once(dirname(__FILE__) . '/elgg/elgg-config/settings.php');

/* Load Multisite Classes */
spl_autoload_register(function($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    $basedir = dirname(__FILE__) . '/multisite/classes/';
    if (file_exists($basedir . $class . '.php')) {
	include_once($basedir . $class . '.php');
    }
});

/* Load symfony */
spl_autoload_register(function($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    $basedir = dirname(__FILE__) . '/multisite/vendor/';
    if (file_exists($basedir . $class . '.php')) {
	include_once($basedir . $class . '.php');
    }
});

// Create new console application
global $console;
$console = new \Symfony\Component\Console\Application('Elgg Multisite', \ElggMultisite\Version::version());

/**
 * Display version
 */
$console
	->register('version')
	->setDescription('Returns the current Elgg Multisite version')
	->setDefinition([])
	->setCode(function (\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output) {
	    $output->writeln(file_get_contents(dirname(__FILE__) . '/version.ini'));
	});

/**
 * List domains
 */
$console
	->register('show')
	->setDescription('List all multisite domains currently created.')
	->setDefinition([])
	->setCode(function (\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output) {
	    $forms = \ElggMultisite\Domain::getDomainTypes(); 
	    if ($domains = \ElggMultisite\Domain::getDomains()) {
		foreach ($domains as $domain)
		{
		    $label = $forms[get_class($domain)];
		    $url = $domain->getDomain();
		    
		    $out = "* $url ($label)";
		    if (!$domain->isSiteAccessible())
			$out .= ': DISABLED';
		    
		    $output->writeln($out);
		}
	    }
	});

	
/**
 * Enable
 */	
$console
	->register('enable')
	->setDescription('Enable a domain')
	->setDefinition([
	    new \Symfony\Component\Console\Input\InputArgument('domain', \Symfony\Component\Console\Input\InputArgument::REQUIRED, 'Domain to enable'),
	])
	->setCode(function (\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output) {
	    if ($d = $input->getArgument('domain')) {
		if ($domain = \ElggMultisite\Domain::getByDomain($d)) {
		    $domain->enable();
		} else {
		    throw new \Exception("Domain $domain could not be found.");
		}
	    }
	});	
		
/**
 * Disable
 */	
$console
	->register('disable')
	->setDescription('Disable a domain')
	->setDefinition([
	    new \Symfony\Component\Console\Input\InputArgument('domain', \Symfony\Component\Console\Input\InputArgument::REQUIRED, 'Domain to disable'),
	])
	->setCode(function (\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output) {
	    if ($d = $input->getArgument('domain')) {
		if ($domain = \ElggMultisite\Domain::getByDomain($d)) {
		    $domain->disable();
		} else {
		    throw new \Exception("Domain $domain could not be found.");
		}
	    }
	});
	
/**
 * Delete a domain
 */	
$console
	->register('destroy')
	->setDescription('Destroy a domain, warning this can not be undone!')
	->setDefinition([
	    new \Symfony\Component\Console\Input\InputArgument('domain', \Symfony\Component\Console\Input\InputArgument::REQUIRED, 'Domain to remove'),
	])
	->setCode(function (\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output) {
	    if ($d = $input->getArgument('domain')) {
		if ($domain = \ElggMultisite\Domain::getByDomain($d)) {
		    $domain->delete();
		} else {
		    throw new \Exception("Domain $domain could not be found.");
		}
	    }
	});	


/**
 * Add a domain
 */	
$console
	->register('new')
	->setDescription('Create a new site, with all plugins enabled by default')
	->setDefinition([
	    new \Symfony\Component\Console\Input\InputArgument('domain', \Symfony\Component\Console\Input\InputArgument::REQUIRED, 'Domain name to add'),
	])
	->setCode(function (\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output) {
	    if ($d = $input->getArgument('domain')) {
		
		\ElggMultisite\Domain::addDomain(
			$d, 
			[], // Use default database settings
			\ElggMultisite\Site::site()->getInstalledPlugins(), 
			'ElggMultisite\\Domain'
		);
		
		$output->writeln("Domain $d created.");
	    }
	});		
	
$console->run();