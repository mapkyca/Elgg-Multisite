<div id="page-messages">
    <?php
    $messages = ElggMultisite\Messages::getMessages();
    if (!empty($messages)) {
	foreach ($messages as $message) {
	    ?>
	    <div class="alert alert-primary" role="alert">
		
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		    <span aria-hidden="true">&times;</span>
		</button>
		<?= $message ?>
	    </div>

	    <?php
	}
    }
    ?>
</div>