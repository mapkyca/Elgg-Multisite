<?php

$form = '/domains/add';
$domain = $vars['domain'];
if (!empty($domain))
    $form = '/domains/edit';
?>
<div class="multisitedomain_edit">
	<form action="<?= $form; ?>" method="post">
		
		<div class="form-group">
		    <label>Domain name: <input type="text" class="domain input-text form-control" name="domain" value="<?= (!empty($domain)) ? $domain->getDomain() : ''; ?>" placeholder="yoursite.multi" required /></label>
		</div>
	    
		<?= $this->draw('forms/domains/dbsettings'); ?>
		<?= $this->draw('forms/domains/plugins'); ?>
	    
		<input type="hidden" name="class" value="ElggMultisite\Domain" />
		<input type="submit" name="Submit" value="Submit" />
	</form>
</div>