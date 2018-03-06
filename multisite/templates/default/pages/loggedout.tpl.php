<?php
    if (\ElggMultisite\User::countUsers()) {
?>
<div class='login'>


    <form action='/session/login' method='post'>
	<div class="form-group">
	    <label>Username: <input class="username input-text form-control" type="text" name="username" value="" /> </label>
	</div>
	<div class="form-group">
	    <label>Password: <input class="password input-password form-control" type="password" name="password" value="" /> </label>
	</div>


	<input class="submit" type="submit" name="Login" value="Login">
    </form>

</div>
    <?php } else {
	?>
<div class="firstuser">
	<div class="firstusertext">
		<p>You have just installed ElggMultisite, you must now create your administrator
		user in order to be able to start configuring your sites!</p>
	</div>
	<?= $this->draw('forms/register'); ?>
</div>
<?php
    }
    