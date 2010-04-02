<?php
	require_once(dirname(dirname(dirname(__FILE__))) . '/multi/start.php');
	
	
	
	$username = trim($_REQUEST['username']);
	$password = trim($_REQUEST['password']);
	$password2 = trim($_REQUEST['password2']);
	
	if (elggmulti_create_user($username, $password, $password2))
		elggmulti_set_message("New user created");
	else	
		elggmulti_set_message("There was a problem creating the new user, are you sure it doesn't already exist?");
	
	header('Location: ' . $_SERVER['HTTP_REFERER']);
?>