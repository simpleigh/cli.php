<?php
/**
 * Output all the colours available
 */

require dirname(__FILE__).'/../lib/FusePump/Cli/Logger.php';

use FusePump\Cli\Logger as Logger;
use FusePump\Cli\Colours as Colours;

$colours = Colours::getForegroundColors();

foreach($colours as $colour) {
    Logger::log(
        $colour,
        array(
            'colour' => $colour,
            'format' => '%s',
            'inputs' => array()
        )
    );
}
