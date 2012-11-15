<?php
/**
 * Log class
 */

require dirname(__FILE__).'/Colours.php';

class Logger {
    /**
     * Output log message
     */
    public static function log($message, $options = array()) {
        if(array_key_exists('colour', $options) && !empty($options['colour'])) {
            echo Colours::string($message, $options['colour']).PHP_EOL;
        } else {
            echo $message.PHP_EOL;
        }
    }
}
