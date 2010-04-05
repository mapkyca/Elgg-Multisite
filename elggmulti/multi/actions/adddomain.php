<?php
	require_once(dirname(dirname(dirname(__FILE__))) . '/multi/start.php');
	
	$class = $_REQUEST['class'];
	$url = $_REQUEST['domain'];

	if ($_SESSION['user']) {
		if ($url)
		{
			switch ($class)
			{
				case 'MultisiteDomain' :
				default:
					if ($url)
					{
						if (strpos($url, 'http')!==true)
							$url = 'http://' . $url;
							
						$domain = new MultisiteDomain($url);
						if ($domain->save())
							elggmulti_set_message('New domain created');
					}
			}
		}
		else
			elggmulti_set_message('You need to provide a valid domain');
	}
	
	header('Location: ' . $_SERVER['HTTP_REFERER']);
?>