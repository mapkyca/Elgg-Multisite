<?php
	require_once(dirname(__FILE__) . '/multi/start.php');
	
	
?>
<html>
	<head>
		<title>Marcus Povey's Multisite Elgg</title>
		<link rel="stylesheet" href="css.css" type="text/css" />
		<script type="text/javascript" language="javascript">
		<!--
			function showhide(id)
			{
				var e = document.getElementById(id);
					
				if(e.style.display == 'none') {
					e.style.display = 'block';
				} else {
					e.style.display = 'none';
				} 
			}
		// -->
		</script> 
	</head>
	
	<body>
	<div class="header">
	
		<?php include(dirname(__FILE__).'/multi/pages/messages.php'); ?>
	
		<?php include(dirname(__FILE__).'/multi/pages/titlebar.php'); ?>
	</div>
	<div class="page_body">
	<?php
		if (elggmulti_countusers() > 0)
		{
		
			$loggedinuser = $_SESSION['user'];
		
			if (!$loggedinuser)
			{
				include(dirname(__FILE__).'/multi/pages/login.php');
			}
			else
			{
				include(dirname(__FILE__).'/multi/pages/mainform.php');
			}
		}
		else
		{
			include(dirname(__FILE__).'/multi/pages/firstuser.php');
		}
	?>
	</div>
	<div class="footer">
		<div class="copyright">MP's Multisite for Elgg is (C) Copyright <a href="http://www.marcus-povey.co.uk">Marcus Povey</a> 2010</div>
	</div>
	</body>
</html>
