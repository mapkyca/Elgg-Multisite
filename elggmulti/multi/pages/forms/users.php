<?php

	$users = elggmulti_getusers();
?>
<div class="users">
<?php
	foreach ($users as $user)
	{
?>
	<div class="user">
		<a href="#" onclick="showhide('disp_<?php echo $user->id; ?>');"><b><?php echo $user->username; ?></b></a>
		
		
		<div id="disp_<?php echo $user->id; ?>" style="display: none;">
			<div class="user_display">
				
				<form action="multi/actions/setpassword.php" method="POST">
			
					<p>
						<label>Password: <input class="password input-password" type="password" name="password" value="" /> </label>
					</p>
					<p>
						<label>Password again: <input class="password input-password" type="password" name="password2" value="" /> </label>
					</p>
					<input type="hidden" name="username" value="<?php echo $user->username; ?>" />
					<input class="submit" type="submit" name="Save" value="Save">
				</form>
				
			
			</div>
		</div>
		
		
		
		<div class="user_strap">
			<?php if ((count($users)>1) && ($_SESSION['user']->username!=$user->username)) {?><a href="multi/actions/deleteuser.php?user_id=<?php echo $user->id; ?>">Delete</a><?php } ?>
		</div>
	</div>
<?php
	}
?>
</div>
<div class="users_forms">
	<div class="users">
		<div class="user">
			<a href="#" onclick="showhide('add_user');">Add a new user...</a>
			
			<div id="add_user" style="display: none;">
				<div class="user_form">
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
				
						<input class="submit" type="submit" name="Save" value="Save">
					</form>		
				</div>
			</div>
		</div>
	</div>
</div>