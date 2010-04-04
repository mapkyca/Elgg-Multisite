<?php
	$forms = MultisiteDomain::getDomainTypes();
?>
<div class="domains">
<?php
	$domains = elggmulti_getdata("SELECT * from domains", '__elggmulti_db_row');
	
	if ($domains)
	{
		foreach ($domains as $domain)
		{
			$label = $forms[get_class($domain)];
			$url = $domain->getDomain();
		?>
		<div class="domain">
			<a href="#" onclick="showhide('add_<?php echo $domain; ?>');"><?php echo $label; ?> <?php echo $url; ?></a>
			<div id="disp_<?php echo $domain; ?>" style="display: none;">
				<div class="domain_display">
				<?php include(dirname(__FILE__)."/../objects/{$d}.php"); ?>
				</div>
			</div>
			<div class="domain_strap">
				Edit :: Delete
			</div>
		</div>	
		<?php
			
		}
	}
?>	
</div>
<div class="domain_forms">
	<?php

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