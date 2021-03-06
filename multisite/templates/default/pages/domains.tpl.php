<?php
	global $version;
		
	$forms = \ElggMultisite\Domain::getDomainTypes(); 
	$domains = \ElggMultisite\Domain::getDomains();
	
?>
<div class="domains card">
    <div class="card-header">Domains</div>
    <div class="card-body">
	<div class="list-group">
<?php
	
	if ($domains)
	{
		foreach ($domains as $domain)
		{
			$label = $forms[get_class($domain)];
			$url = $domain->getDomain();
		
			$d = strtolower(get_class($domain));
			
			ob_start();
//			error_log(var_export($domain, true));
			try {
		?>
		<div class="list-group-item domain<?php if ($domain->enabled=='no') echo " disabled"; ?><?php if (!$domain->isSiteAccessible()) echo " inaccessible"; ?>">
			<a href="#disp_<?php echo $domain->getID(); ?>" data-toggle="collapse"><b><?php echo $url; ?></b> (<?php echo $label; ?>) </a>
			<div id="disp_<?php echo $domain->getID(); ?>" class="collapse">
				<div class="domain_display">
				<?= $this->__(['domain' => $domain])->draw('domains/Domain'); ?>
				</div>
			</div>
			<div class="domain_strap">
				<a class="confirmlink" data-confirmtext="Are you sure you want to delete this site? This can not be undone!" href="/domains/delete?domain_id=<?php echo $domain->getID(); ?>">Delete</a> <?php
				if (!$domain->isSiteAccessible())
				{
					?>
					:: <a href="/domains/enable?domain_id=<?php echo $domain->getID(); ?>">Enable</a>
					<?php
				}
				else
				{
					?>
					:: <a class="confirmlink" href="/domains/disable?domain_id=<?php echo $domain->getID(); ?>">Disable</a>
					<?php
				
				
				
				
				    if ($domain->isDbInstalled()==0)
				    {
					    ?>
					    :: <a href="http://<?php echo $domain->getDomain(); ?>/install.php" target="_blank">Install</a>
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
				}
				
				?>
			</div>
		</div>	
		<?php
		    echo ob_get_clean();
		    } catch (\Exception $e) {
			
			ob_get_clean();
		    }

		    
		}
	} else {
	    ?>
	    No domains available...
	    <?php
	}
?>	
	</div>
    </div>
</div>
<br />
<div class="domain_forms card">
    <div class="card-header">New domain</div>
    <div class="card-body">
	<?php

	unset($this->vars['domain']);
		if ($forms)
		{
			foreach ($forms as $domain => $label) {	
				
	?>		
			<div class="domain">
				<a href="#add_<?= $domain; ?>" data-toggle="collapse">Add a new <?php echo $label; ?>...</a>
				
				<div id="add_<?= $domain; ?>" class="collapse">
					<div class="domain_form">
				
					    <?= $this->draw('domains/Domain'); ?>
					</div>
				</div>
			</div>
	<?php		
			}
		}
	?>
    </div>
</div>