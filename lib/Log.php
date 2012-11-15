<?php
/**
 * Log class
 */

require dirname(__FILE__).'/Colours.php';

class Logger {
    protected static $format = "[%s] [%s] [%s] %s";

    /**
     * Output log message
     */
    public static function log($message, $options = array()) {
        $inputs = array(
            self::$format,
            self::getTimestamp(),
            'log',
            self::getFilename(),
            $message
        );

        if(array_key_exists('colour', $options) && !empty($options['colour'])) {
            echo Colours::string(self::getLogMessage($inputs), $options['colour']).PHP_EOL;
        } else {
            echo self::getLogMessage($inputs).PHP_EOL;
        }
    }

    /**
     * Get timestamp
     */
    public static function getTimestamp() {
        return date('Y-m-d H:i:s');
    }

    /**
     * Get log message
     */
    public static function getLogMessage($inputs) {
        return call_user_func_array('sprintf', $inputs);
    }

    /**
     * Get filename of script calling logger
     */
    public static function getFilename() {
        $bt = debug_backtrace();
        return basename($bt[1]['file']);
    }
}
