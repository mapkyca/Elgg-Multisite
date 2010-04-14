<?php
	require_once(dirname(dirname(dirname(__FILE__))) . '/multi/start.php');

	$domain_id = (int)$_REQUEST['domain_id'];
	
	if ($_SESSION['user']) {

		$result = elggmulti_get_db_by_id($domain_id);
	
		if ($result) {
			$result->enabled = 'no';
			
			$result->save();
			elggmulti_set_message("Domain disabled.");
			
		}
	}
	
	header('Location: ' . $_SERVER['HTTP_REFERER']);
?>