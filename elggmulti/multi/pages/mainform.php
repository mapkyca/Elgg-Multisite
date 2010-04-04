<?php

	$context = $_REQUEST['_em_ct'];
	if (!$context)
		$context = 'domains';
?>
<div class="mainform">

	<div class="mainnav">
		<?php require_once(dirname(__FILE__) . '/mainnav.php'); ?>
	</div>
	
	<div class="content">

		<div class="form_menu">
			<?php require_once(dirname(__FILE__) . "/forms/{$context}_nav.php"); ?>
		</div>
		<div class="form">
		<?php
			$file = dirname(__FILE__) . "/forms/$context.php";
			
			if (file_exists($file))
				require_once($file);
		?>
		</div>
	</div>
</div>