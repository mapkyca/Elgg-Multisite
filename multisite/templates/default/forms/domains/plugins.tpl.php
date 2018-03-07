<?php
	static $plugins;
	
	if (!$plugins)
		$plugins = ElggMultisite\Domain::getInstalledPlugins();

	if ($domain instanceof \ElggMultisite\Domain) 
		$activated = $domain->getActivatedPlugins();
	else
		$activated = $plugins;

?>
<div class="card">
    <div class="card-header">Available plugins</div>
    <div class="card-body">
	<div class="list-group">
	<?php
		foreach ($plugins as $plugin)
		{
	?>
    <div class="form-group list-group-item">
		<label><input class="form-control" type="checkbox" name="available_plugins[]" value="<?php echo $plugin; ?>" <?php if (in_array($plugin, $activated)) echo 'checked="true"'; ?> /> <?php echo $plugin; ?></label>
    </div>
	<?php	
		}
	?>
	</div>
    </div>
</div>