<?php
	$messages = elggmulti_get_messages();
?>
<div class="messages">
    <?php if ($messages) { ?>
    <div class="message">
	<ul>
	<?php
	
		foreach ($messages as $message)
		{
		?>
	<li><?php echo $message; ?></li>
		<?php	
		}	
?>
	</ul>
    </div>
    <?php } ?>
</div>