<?php

register_shutdown_function(function () {
    $error = error_get_last();
    if ($error["type"] == E_ERROR) {

	try {
	    ob_clean();
	} catch (ErrorException $e) {
	    
	}

	http_response_code(500);

	if (!empty($_SERVER['SERVER_NAME'])) {
	    $server_name = $_SERVER['SERVER_NAME'];
	} else {
	    $server_name = '';
	}
	if (!empty($_SERVER['REQUEST_URI'])) {
	    $request_uri = $_SERVER['REQUEST_URI'];
	} else {
	    $request_uri = '';
	}

	$error_message = "Fatal Error: {$error['file']}:{$error['line']} - \"{$error['message']}\", on page {$server_name}{$request_uri}";

	echo "<h1>Elgg Multisite experienced a problem</h1>";
	echo "<p>Sorry, we experienced a problem with this page and couldn't continue. The technical details are as follows:</p>";
	echo "<pre>$error_message</pre>";

	error_log($error_message);

	exit;
    }
});

set_exception_handler(function ($exception) {
    try {
	ob_clean();
    } catch (ErrorException $e) {

    }

    http_response_code(500);
    error_log($exception->getMessage());
    
    echo "<h1>Elgg Multisite experienced a problem</h1>";
    echo "<p>Sorry, we experienced a problem with this page and couldn't continue. The technical details are as follows:</p>";
    echo "<pre>".$exception."</pre>";

});

require_once(dirname(dirname(__FILE__)) . '/elgg/elgg-config/settings.php');
session_start();

spl_autoload_register(function($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    $basedir = dirname(__FILE__) . '/classes/';
    if (file_exists($basedir . $class . '.php')) {
	include_once($basedir . $class . '.php');
    }
});

require_once(dirname(__FILE__) . '/vendor/bonita/start.php');
\Bonita\Main::additionalPath(dirname(__FILE__));

require_once(dirname(__FILE__) . '/vendor/torophp/src/Toro.php');

$site = ElggMultisite\Site::site();
try {
    $site->checkInstall();
} catch (Exception $ex) {
    ElggMultisite\Messages::addMessage($ex);
}


\ElggMultisite\PageHandler::serve([
    '/?' => '\ElggMultisite\Pages\LoggedOut',
    'users/?' => '\ElggMultisite\Pages\Users',
    'domains/?' => '\ElggMultisite\Pages\Domains',
    'domains/add/?' => '\ElggMultisite\Pages\Domains\Add',
    'session/login/?' => '\ElggMultisite\Pages\Session\Login',
    'session/logout/?' => '\ElggMultisite\Pages\Session\Logout',
    'session/register/?' => '\ElggMultisite\Pages\Session\Register',
    'session/setpassword/?' => '\ElggMultisite\Pages\Session\SetPassword',
    'session/deleteuser/?' => '\ElggMultisite\Pages\Session\Delete',
]);
