<?php
$this->draw('shell/headers');
?>
<!DOCTYPE html>
<html>
    <head>

	<?php
	echo $this->draw('shell/metatags');
	echo $this->draw('shell/bootstrap');
	echo $this->draw('shell/javascript');
	echo $this->draw('shell/css');
	echo $this->draw('shell/head');
	?>

    </head>

    <body>

	<?= $this->draw('shell/nav'); ?>

	<div class="page-container">
	    <div class="container page-body">
		<?= $this->draw('shell/messages') ?>
		
		<?php
		if (!empty($vars['body']))
		    echo $vars['body'];
		?>
		
		
	<?php
	echo $this->draw('shell/footer');
	?>
	    </div>
	</div>

    </body>
</html>
