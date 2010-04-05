<?php

?>
<div class="multisitedomain">
	<form action="multi/actions/editdomain.php">
		<input type="hidden" name="domain_id" value="<?php echo $domain->getID(); ?>" />
		<label>Domain name: <input type="text" class="domain input-text" name="domain" value="<?php echo $domain->getDomain(); ?>" /></label>
		<?php include(dirname(__FILE__)."/../dbsettings.php"); ?>
		<input type="submit" name="Submit" value="Submit" />
	</form>
</div>