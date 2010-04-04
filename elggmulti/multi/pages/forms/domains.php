<?php

?>
<div class="domains">



	<?php

		$forms = MultisiteDomain::getDomainTypes();
		
		if ($forms)
		{
			foreach ($forms as $domain => $label) {	
				
				$d = strtolower($domain);
	?>		
			<div class="domain">
				<a href="#" onclick="showhide('add_<?php echo $domain; ?>');">Add a new <?php echo $label; ?>...</a>
				
				<div id="add_<?php echo $domain; ?>" style="display: none;">
					<div class="domain_form">
					<?php include(dirname(__FILE__)."/../objects/{$d}_edit.php"); ?>
					</div>
				</div>
			</div>
	<?php		
			}
		}
	?>
</div>