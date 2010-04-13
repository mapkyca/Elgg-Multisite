<?php
	static $plugins;
	
	if (!$plugins)
		$plugins = elggmulti_get_installed_plugins();

	$activated = elggmulti_get_activated_plugins($domain->getID())

?>
<h2>Available plugins</h2>
<select name="available_plugins[]">
	<?php
		foreach ($plugins as $plugin)
		{
	?>
		<option value="<?php echo $plugin; ?>" <?php if (in_array($plugin, $plugins)) echo 'selected="yes"'; ?>><?php echo $plugin; ?></option>
	<?php	
		}
	?>
</select>