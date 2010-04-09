<?php
	require_once(dirname(dirname(dirname(__FILE__))) . '/multi/start.php');
	
	global $CONFIG;
	
	$class = $_REQUEST['class'];
	$url = $_REQUEST['domain'];
	

	if ($_SESSION['user']) {
		if ($url)
		{
			$domain = false;
			
			switch ($class)
			{
				case 'MultisiteDomain' :
				default:
					if ($url)
						$domain = new MultisiteDomain();
			}
			
			if ($domain) {
				
				// Common url settings
				$domain->setDomain($url);
				
				// Common db settings
				$domain->dbname = $_REQUEST['dbname'];
				if (!$domain->dbname) {
					foreach (array('.',',',':','/','\\','\'','"','$','£','!','(',')','{','}') as $char)
						$url = str_replace($char,'_', $url);
					
					$domain->dbname= $url;
				}
				
				$domain->dbuser = $_REQUEST['dbuser'];
				if (!$domain->dbuser)
					$domain->dbuser=$CONFIG->multisite->dbuser;
					
				$domain->dbpass = $_REQUEST['dbpass'];
				if (!$domain->dbpass)
					$domain->dbpass=$CONFIG->multisite->dbpass;
					
				$domain->dbhost = $_REQUEST['dbhost'];
				if (!$domain->dbhost)
					$domain->dbhost=$CONFIG->multisite->dbhost;
					
				$domain->dbprefix = $_REQUEST['dbprefix'];
				if (!$domain->dbprefix)
					$domain->dbprefix = 'elgg';
				
				$domain->dataroot = dirname(dirname(dirname(__FILE__))) . "/data/$url/";
				@mkdir($domain->dataroot);

				$dbname = mysql_real_escape_string($domain->dbname);
				$dbuser = mysql_real_escape_string($domain->dbuser);
				$dbpass = mysql_real_escape_string($domain->dbpass);
				$dbhost = mysql_real_escape_string($domain->dbhost);
					
				// Try and create the database using registered 
				if (!elggmulti_execute_query("CREATE database $dbname"))
					elggmulti_set_message("Could not create database $dbname@$dbhost, perhaps it already exists?");
					
				if (!elggmulti_execute_query("grant all on $dbname.* to `$dbuser`@`$dbhost` identified by '$dbpass'"))
					elggmulti_set_message("Unable to grant access on $dbname@$dbhost, please do this manually or you will likely have problems");
				
				// Save
				if ($domain->save())
					elggmulti_set_message('New domain created');
			}
		}
		else
			elggmulti_set_message('You need to provide a valid domain');
	}
	
	header('Location: ' . $_SERVER['HTTP_REFERER']);
?>