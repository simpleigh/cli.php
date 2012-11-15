<?php
/**
 * Output all the colours available
 */

require dirname(__FILE__).'/../lib/Logger.php';

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
