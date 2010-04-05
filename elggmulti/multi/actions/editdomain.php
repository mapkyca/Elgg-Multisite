<?php
	require_once(dirname(dirname(dirname(__FILE__))) . '/multi/start.php');
	
	global $CONFIG;
	
	$id = (int)$_REQUEST['domain_id'];
	$url = $_REQUEST['domain'];
	

	if ($_SESSION['user']) {
		if ($url)
		{
			$domain = elggmulti_getdata_row("SELECT * from domains WHERE id=$id limit 1", '__elggmulti_db_row');

			if ($domain) {
				
				// Common url settings
				$domain->setDomain($url);
				
				// Common db settings
				$domain->dbname = $_REQUEST['dbname'];
				if (!$domain->dbname)
					$domain->dbname=$CONFIG->multisite->dbname;
				
				$domain->dbuser = $_REQUEST['dbuser'];
				if (!$domain->dbuser)
					$domain->dbuser=$CONFIG->multisite->dbuser;
					
				$domain->dbpass = $_REQUEST['dbpass'];
				if (!$domain->dbpass)
					$domain->dbpass=$CONFIG->multisite->dbpass;
					
				$domain->dbhost = $_REQUEST['dbhost'];
				if (!$domain->dbhost)
					$domain->dbhost=$CONFIG->multisite->dbhost;
				
				// Save
				if ($domain->save())
					elggmulti_set_message('Domain details updated');
			}
		}
		else
			elggmulti_set_message('You need to provide a valid domain');
	}
	
	header('Location: ' . $_SERVER['HTTP_REFERER']);
?>