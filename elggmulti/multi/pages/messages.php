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
    <script>
	<!--
	$(document).ready(function() {
	      $('div.messages div.message').click(function(){
		    $(this).fadeOut();
	      })

	      setTimeout(function() { $('div.messages div.message').fadeOut(); }, 5000);
	});
	    
	-->
    </script>
    <?php } ?>
</div>