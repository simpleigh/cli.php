<?php
/**
 * Timing examples
 */

require dirname(__FILE__).'/../lib/FusePump/Cli/Timer.php';

use FusePump\Cli\Timer as Timer;

$clock = new Timer();

$clock->start('total');

$clock->start('block1');
sleep(2);
$clock->stop('block1');

$clock->start('block2');
sleep(3);
$clock->stop('block2');

// Averages
for($i = 0; $i < 5; $i++) {
    $rand = rand(0, 10)/10;
    $clock->startAvg('loop');
    sleep2($rand);
    $clock->stopAvg('loop');
}

$clock->stop('total');

echo $clock->report();

echo $clock->report('total');

$block1 = $clock->get('block1');
print_r($block1);
$avg = $clock->getAvg('loop');
print_r($avg);

/**
 * sleep2
 *
 * Function to allow sleeping in periods less than a second
 */
function sleep2($seconds) {
    $seconds = abs($seconds);
    if($seconds < 1) {
        usleep($seconds * 1000000);
    } else {
        sleep($seconds);
    }
}
