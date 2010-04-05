<?php

?>
<div class="multisitedomain_edit">
	<form action="multi/actions/adddomain.php">
		
		<label>Domain name: <input type="text" class="domain input-text" name="domain" value="" /></label>
		<?php include(dirname(__FILE__)."/../dbsettings.php"); ?>
		<input type="hidden" name="class" value="MultisiteDomain" />
		<input type="submit" name="Submit" value="Submit" />
	</form>
</div>