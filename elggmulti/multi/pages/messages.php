<?php
	$messages = elggmulti_get_messages();
?>
<div class="messages">
<?php
	if ($messages) {
		foreach ($messages as $message)
		{
		?>
	<div class="message">
		<p><?php echo $message; ?></p>
	</div>
		<?php	
		}
	}
?>
</div>