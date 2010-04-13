<?php
	static $plugins;
	
	if (!$plugins)
		$plugins = elggmulti_get_installed_plugins();

	$activated = elggmulti_get_activated_plugins($domain->getID());

?>
<h2>Available plugins</h2>
<p>
	<?php
		foreach ($plugins as $plugin)
		{
	?>
		<label><input type="checkbox" name="available_plugins[]" value="<?php echo $plugin; ?>" <?php if (in_array($plugin, $activated)) echo 'checked="true"'; ?> /> <?php echo $plugin; ?></label><br />
	<?php	
		}
	?>
</p>