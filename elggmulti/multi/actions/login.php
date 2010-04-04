<?php
	require_once(dirname(dirname(dirname(__FILE__))) . '/multi/start.php');
	
	
	$username = trim($_REQUEST['username']);
	$password = trim($_REQUEST['password']);
	
	if (elggmulti_login($username, $password))
		elggmulti_set_message("User logged in successfully");
	else	
		elggmulti_set_message("Sorry, we were unable to log you in.");
	
	header('Location: ' . $_SERVER['HTTP_REFERER']);
?>