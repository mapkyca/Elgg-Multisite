<?php
$users = \ElggMultisite\User::getUsers();

?>
<div class="users card">
    <div class="card-header">Users</div>
    <div class="card-body">
    <ul class="list-group">
    <?php
    foreach ($users as $user) {
	?>
    
    <li class="list-group-item">
		<a href="#disp_<?php echo $user->id; ?>" data-toggle="collapse"><b><?php echo $user->username; ?></b></a>
		
		
		<div id="disp_<?php echo $user->id; ?>" class="collapse">
		    <br />
		    <div class="card">
			<div class="user_display card-body">
				
				<form action="/session/setpassword" method="POST">
			
					<div class="form-group">
						<label>Password: <input class="password input-password form-control" type="password" name="password" value="" /> </label>
					</div>
					<div class="form-group">
						<label>Password again: <input class="password input-password form-control" type="password" name="password2" value="" /> </label>
					</div>
					<input type="hidden" name="username" value="<?php echo $user->username; ?>" />
					<input class="submit" type="submit" name="Save" value="Change password...">
				</form>
				
			
			</div>
		    </div>
		</div>
		
		
		
		<div class="user_strap">
			<?php if ((count($users)>1) && ($_SESSION['em_user']->username!=$user->username)) {?><a href="session/deleteuser?user_id=<?php echo $user->id; ?>">Delete</a><?php } ?>
		</div>
    </li>
    
    
    <?php } ?>  
    </ul>
    </div>
</div>
<!--
<br />
<div class="users_forms card">
    <div class="card-header">Add a new user...</div>
	<div class="card-body">
		<?= $this->draw('forms/register'); ?>
	</div>
</div>-->