<?php

?>
<div class="registeruser">
	<form action="multi/actions/register.php" method="POST">

		<p>
			<label>Username: <input class="username input-text" type="text" name="username" value="<?php echo $user->username; ?>" /> </label>
		</p>
		<p>
			<label>Password: <input class="password input-password" type="password" name="password" value="" /> </label>
		</p>
		<p>
			<label>Password again: <input class="password input-password" type="password" name="password2" value="" /> </label>
		</p>

		<input class="submit" type="submit" name="Register" value="Register">
	</form>
</div>