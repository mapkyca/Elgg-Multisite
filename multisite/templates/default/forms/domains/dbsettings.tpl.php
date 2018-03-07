<?php
global $CONFIG;
$domain = $vars['domain'];
?>

<div class="card">
    <div class="card-header">Database settings</div>
    <div class="card-body">
	<ul class="list-group">
	    <div class="form-group list-group-item">
		<label>Database name (blank for default): 
		    <input type="text" class="dbname input-text form-control" name="dbsettings[dbname]" value="<?php echo $domain->dbname; ?>" placeholder="<?= $CONFIG->multisite->dbname; ?>" />
		</label>
	    </div>
	    <div class="form-group list-group-item">
		<label>Database user (blank for default): 
		    <input type="text" class="dbuser input-text form-control" name="dbsettings[dbuser]" value="<?php echo $domain->dbuser; ?>" placeholder="<?= $CONFIG->multisite->dbuser; ?>" />
		</label>
	    </div>
	    <div class="form-group list-group-item">
		<label>Database password (blank for default): 
		    <input type="password" class="dbpass input-password form-control" name="dbsettings[dbpass]" value="<?php echo $domain->dbpass; ?>" placeholder="Your multisite password" />
		</label>
	    </div>
	    <div class="form-group list-group-item">
		<label>Database host (blank for default): <input type="text" class="dbhost input-text form-control" name="dbsettings[dbhost]" value="<?php echo $domain->dbhost; ?>" placeholder="<?= $CONFIG->multisite->dbhost; ?>" /></label>
	    </div>
	    <div class="form-group list-group-item">
		<label>Database prefix (blank for default): <input type="text" class="dbprefix input-text form-control" name="dbsettings[dbprefix]" value="<?php echo $domain->dbprefix; ?>" placeholder="<?= $CONFIG->multisite->dbprefix; ?>" /></label>
	    </div>
    </div>
</div>
<br />