<?php
/**
 * Sandwich maker
 */
require(dirname(__FILE__).'/../lib/FusePump/Cli/Inputs.php');

use FusePump\Cli\Inputs as Inputs;

$cli = new Inputs($argv);

$cli->option('-h, --ham', 'Add ham');
$cli->option('-m, --mayo', 'Add mayonaise');
$cli->option('-c, --cheese [type]', 'Add a cheese');
$cli->option('-b, --bread [type]', 'Type of bread', true); // required input

$cli->param('when', 'When do you want the sandwhich (now, tomorrow or never)', true);

if(!$cli->parse()) {
    exit(1);
}

echo "You ordered a sandwich ".$cli->get('when')." with: \n";
if($cli->get('-h')) echo " - Ham \n";
if($cli->get('-m')) echo " - Mayonaise \n";
if($cli->get('--cheese')) echo ' - '.$cli->get('--cheese')." cheese \n";
echo 'On '.$cli->get('-b')." bread \n";

