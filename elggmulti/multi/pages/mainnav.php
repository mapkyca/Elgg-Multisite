<?php
	global $context;
	
	$pages = array(
		'domains' => 'Domains'
	);
?>
<div class="navbar">
	<ul>
		<?php

			foreach ($pages as $cont => $label)
			{
				?>
				<li class="<?php echo $cont; ?><?php 
					if ($context == $cont)
					{
						echo " selected";
					}
				?>">
				<a href="index.php?_em_ct=<?php echo $cont; ?>"><?php echo $label ?></a>
				</li>
				<?php
			}
		
		?>
	</ul>
</div>