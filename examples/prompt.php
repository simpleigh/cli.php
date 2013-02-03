<?php
/**
 * Prompt for info
 */
require(dirname(__FILE__).'/../lib/FusePump/Cli/Inputs.php');

use FusePump\Cli\Inputs as Inputs;

$cli = new Inputs($argv);

if(!$cli->parse()) {
    exit(1);
}

$username = $cli->prompt('Username: ');
echo 'Got '.$username."\n";

$password = $cli->prompt('Password (not a real one): ', true);
echo 'Got '.$password."\n";

$confirm = $cli->confirm('Confirm? (y/n) ');
echo var_dump($confirm);

