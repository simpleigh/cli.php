# CLI php helper

A small collection of php classes to help with CLI utilities. 

## Inputs.php

Parse command line inputs

__Example:__

	<?php
	require('lib/Inputs.php');
	$cli = new Inputs($argv);
	
	$cli->option('-h, --ham', 'Add ham');
	$cli->option('-m, --mayo', 'Add mayonaise');
	$cli->option('-c, --cheese [type]', 'Add a cheese');
	$cli->option('-b, --bread [type]', 'Type of bread', true); // required input
	
	if(!$cli->parse()) {
		exit(1);	
	}
	
	echo "You ordered a sandwich with: \n";
	if($cli->get('-h')) echo " - Ham \n";
	if($cli->get('-m')) echo " - Mayonaise \n";
	if($cli->get('--cheese')) echo ' - '.$cli->get('--cheese')." cheese \n";
	echo 'On '.$cli->get('-b')." bread \n";

__Run:__

	php cli.php -h -c cheddar --bread white

__Gives:__
	
	You ordered a sandwich with:
	 - Ham
	 - Cheddar cheese
	On white bread

### Prompts

Prompt the user for information.

__Example:__
	
	<?php
	require('lib/Inputs.php');
	$cli = new Inputs();

	$username = $cli->prompt('Username: ');
	echo 'Got '.$username."\n";

	$password = $cli->prompt('Password (not a real one): ', true);
	echo 'Got '.$password."\n";

	$confirm = $cli->confirm('Confirm? ');
	echo var_dump($confirm);

__N.B.__ Confirm returns true on a "y" or "yes". It returns false otherwise. 

