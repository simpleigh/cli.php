<?php
/**
 * Log class
 */

require dirname(__FILE__).'/Colours.php';

class Logger {
    public static $format = "[%s] [%s] [%s] %s";
    public static $inputs = array();

    /**
     * Output log message
     */
    public static function log($message, $options = array()) {
        if(array_key_exists('inputs', $options)) {
            $inputs = $options['inputs'];
        } else {
            $inputs = array(
                self::getTimestamp(),
                'log',
                self::getFilename()
            );
        }

        if(array_key_exists('format', $options)) {
            array_unshift($inputs, $options['format']);
        } else {
            array_unshift($inputs, self::$format);
        }

        $inputs[] = $message;

        $logmessage = self::getLogMessage($inputs);
        if(array_key_exists('colour', $options) && !empty($options['colour'])) {
            echo Colours::string($logmessage, $options['colour']).PHP_EOL;
        } else {
            echo $logmessage.PHP_EOL;
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
    private static function getFilename() {
        $bt = debug_backtrace();
        $file = $bt[1];
        if(isset($file['file'])) {
            return basename($file['file']);
        } else {
            return '';
        }
    }
}
