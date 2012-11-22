<?php
/**
 * Timing examples
 */

require dirname(__FILE__).'/../lib/Timer.php';

$clock = new Timer();

$clock->start('total');

$clock->start('block1');
sleep(2);
$clock->stop('block1');

$clock->start('block2');
sleep(3);
$clock->stop('block2');

$clock->stop('total');

$clock->report();

$clock->report('total');
