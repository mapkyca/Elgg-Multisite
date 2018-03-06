<?php

?>
<div class="registeruser">
	<form action="/session/register" method="POST">

		<div class="form-group">
			<label>Username: <input class="username input-text form-control" type="text" name="username" value="<?php echo $user->username; ?>" /> </label>
		</div>
		<div class="form-group">
			<label>Password: <input class="password input-password form-control" type="password" name="password" value="" /> </label>
		</div>
		<div class="form-group">
			<label>Password again: <input class="password input-password form-control" type="password" name="password2" value="" /> </label>
		</div>

		<input class="submit" type="submit" name="Register" value="Register">
	</form>
</div>