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
			<div class="domain_display">
			
			
			
			
			
			
			
			
			
			</div>
		</div>
		
		
		
		<div class="user_strap">
			<a href="multi/actions/deleteuser.php?user_id=<?php echo $user->id; ?>">Delete</a>
		</div>
	</div>
<?php
	}
?>
</div>
<div class="users_forms">
	<div class="users">
		<a href="#" onclick="showhide('add_user');">Add a new user...</a>
		
		<div id="add_user" style="display: none;">
			<div class="user_form">
			







			</div>
		</div>
	</div>
</div>