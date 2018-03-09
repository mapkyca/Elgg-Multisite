<?php
	static $plugins;
	
	if (!$plugins)
		$plugins = ElggMultisite\Site::site()->getInstalledPlugins();

	$domain = $vars['domain'];
	if ($domain instanceof \ElggMultisite\Domain) 
		$activated = ElggMultisite\Site::site()->getActivatedPlugins($domain->getID());
	else
		$activated = $plugins;


?>
<div class="card">
    <div class="card-header">Available plugins</div>
    <div class="card-body">
	<table class="table">
	    <thead class="thead-dark">
		<th scope="col">Plugin</th>
		<th scope="col">Activate</th>
	    </thead>
	<?php
		foreach ($plugins as $plugin)
		{
		    if ($plugin == 'elggmultisite') {
			?>
		<input class="form-control" type="hidden" name="available_plugins[]" value="<?php echo $plugin; ?>" />
	    <?php
		    } else {
	?>
	    <tr>
		<td><?php echo $plugin; ?></td>
		<td><input class="form-control" type="checkbox" name="available_plugins[]" value="<?php echo $plugin; ?>" <?php if (in_array($plugin, $activated)) echo 'checked="true"'; ?> /> </td>
	    </tr>
	<?php	
		    }
		}
	?>
	</table>
    </div>
</div>