<?php
	global $version;
		
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
		
			$d = strtolower(get_class($domain));
		?>
		<div class="domain">
			<a href="#" onclick="showhide('disp_<?php echo $domain->getID(); ?>');"><b><?php echo $url; ?></b> (<?php echo $label; ?>) </a>
			<div id="disp_<?php echo $domain->getID(); ?>" style="display: none;">
				<div class="domain_display">
				<?php include(dirname(__FILE__)."/../objects/{$d}.php"); ?>
				</div>
			</div>
			<div class="domain_strap">
				<a href="multi/actions/deletedomain.php?domain_id=<?php echo $domain->getID(); ?>">Delete</a> <?php
				if ($domain->isDbInstalled()==0)
				{
					?>
					:: <a href="http://<?php echo $domain->getDomain(); ?>" target="_blank">Install</a>
					<?php
				}
				else
				{
					?>
					:: <a href="http://<?php echo $domain->getDomain(); ?>" target="_blank">Visit site</a>
					<?php
				}
				
				if (($domain->getDBVersion()>0) && ($domain->getDBVersion()<$version))
				{
					?>
					:: <a href="http://<?php echo $domain->getDomain(); ?>/upgrade.php" target="_blank">Upgrade DB</a>
					<?php
				}
				?>
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