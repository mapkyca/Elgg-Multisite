#!/usr/bin/php -q
<?php

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
$console = new \Symfony\Component\Console\Application();

$console
	->register('version')
	->setDescription('Returns the current Elgg Multisite version')
	->setDefinition([])
	->setCode(function (\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output) {
	    $output->writeln(file_get_contents(dirname(__FILE__) . '/version.ini'));
	});

	
	
	
	
$console->run();