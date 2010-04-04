<?php
	require_once(dirname(dirname(dirname(__FILE__))) . '/multi/start.php');
	
	
	if (elggmulti_logout())
		elggmulti_set_message("User logged out");
	else	
		elggmulti_set_message("Sorry, there was a problem logging you out.");
	
	header('Location: ' . $_SERVER['HTTP_REFERER']);
?>