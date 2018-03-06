<?php

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


\ElggMultisite\PageHandler::serve([
    '/?' => '\ElggMultisite\Pages\LoggedOut',
    'users/?' => '\ElggMultisite\Pages\Users',
    'domains/?' => '\ElggMultisite\Pages\Domains',
    'session/login/?' => '\ElggMultisite\Pages\Session\Login',
    'session/logout/?' => '\ElggMultisite\Pages\Session\Logout',
    'session/register/?' => '\ElggMultisite\Pages\Session\Register',
    'session/setpassword/?' => '\ElggMultisite\Pages\Session\SetPassword',
    'session/deleteuser/?' => '\ElggMultisite\Pages\Session\Delete',
]);