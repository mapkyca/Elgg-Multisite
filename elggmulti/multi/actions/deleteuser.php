<?php
	require_once(dirname(dirname(dirname(__FILE__))) . '/multi/start.php');

	$user_id = (int)$_REQUEST['user_id'];
	
	if ($_SESSION['user']) {
		
		if (
			elggmulti_execute_query("DELETE from users WHERE id=$user_id")
		)
			elggmulti_set_message('User removed.');
	}
	
	header('Location: ' . $_SERVER['HTTP_REFERER']);
?>