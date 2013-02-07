<?php
	require_once(dirname(__FILE__) . '/multi/start.php');
	
	header("Content-type: text/html; charset=UTF-8");
	
?><!DOCTYPE html>
<html>
	<head>
	    <meta charset="utf-8" />
	    <title>Marcus Povey's Multisite Elgg</title>
	    <link rel="stylesheet" href="css.css" type="text/css" />
	    <script type="text/javascript" src="multi/vendor/h5f.min.js" /></script>
	    <script type="text/javascript" src="multi/vendor/jquery-1.7.2.min.js" /></script>
	    <script type="text/javascript" language="javascript">
            <meta name="robots" content="noindex" />
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
		<div class="copyright">MP's Multisite for Elgg is (C) Copyright <a href="http://www.marcus-povey.co.uk">Marcus Povey</a> 2010-<?php echo date('y'); ?></div>
	</div>
	</body>
</html>
