<?php

?>
<div class="titlebar">
<?php 

	if ($_SESSION['user'])
	{
?>
<div class="logbar">
	<a href="multi/actions/logout.php">Logout <?php echo $_SESSION['user']->username; ?></a>
</div>
<?php
	}

?>
	<h1>Multisite Elgg Admin</h1>
</div>
