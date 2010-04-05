<?php
	require_once(dirname(dirname(dirname(__FILE__))) . '/multi/start.php');

	$domain_id = (int)$_REQUEST['domain_id'];
	
	if ($_SESSION['user']) {
		
		if (
			elggmulti_execute_query("DELETE from domains WHERE id=$domain_id") && 
			elggmulti_execute_query("DELETE from domains_metadata WHERE domain_id=$domain_id")
		)
			elggmulti_set_message('Domain successfully removed.');
	}
	
	header('Location: ' . $_SERVER['HTTP_REFERER']);
?>