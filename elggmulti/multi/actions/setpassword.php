<?php
	require_once(dirname(dirname(dirname(__FILE__))) . '/multi/start.php');
	
	
	
	$username = trim($_REQUEST['username']);
	$password = trim($_REQUEST['password']);
	$password2 = trim($_REQUEST['password2']);
	
	if (elggmulti_set_user_password($username, $password, $password2))
		elggmulti_set_message("User password reset");
	else	
		elggmulti_set_message("There was a problem setting user password.");
	
	header('Location: ' . $_SERVER['HTTP_REFERER']);
?>