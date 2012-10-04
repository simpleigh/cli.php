# CLI php helper

A small collection of php classes to help with CLI utilities. 

## Inputs

Parse command line inputs

__Example:__

	<?php
	require('lib/Inputs.php');
	$cli = new Inputs();
	
	$cli->option('-h, --ham', 'Add ham');
	$cli->option('-m, --mayo', 'Add mayonaise');
	$cli->option('-c, --cheese [type]', 'Add a cheese');
	$cli->option('-b, --bread [type]', 'Type of bread', true);
	
	echo "You ordered a sandwich with: \n";
	
	if($cli->get('p')) echo " - Ham \n";
	if($cli->get('m')) echo " - Mayonaise \n";
	if($cli->get('cheese')) echo ' - '.$cli->get('cheese')." cheese \n";
	echo 'On '.$cli->get('b')." bread \n";

__Run:__

	php cli.php -h -c cheddar --bread white

__Gives:__
	
	You ordered a sandwich with:
	 - Ham
	 - Cheddar cheese
	On white bread

