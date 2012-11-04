<?php
/**
 * Prompt for info
 */
require(dirname(__FILE__).'/../lib/Inputs.php');
$cli = new Inputs($argv);

if(!$cli->parse()) {
	exit(1);	
}

$username = $cli->prompt('Username: ');
echo 'Got '.$username."\n";

$password = $cli->prompt('Password (not a real one): ', true);
echo 'Got '.$password."\n";

$confirm = $cli->confirm('Confirm? ');
echo var_dump($confirm);

