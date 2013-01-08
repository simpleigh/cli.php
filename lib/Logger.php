<?php
/**
 * Log class
 */

require dirname(__FILE__).'/Colours.php';

class Logger {
    public static $format = "[%s] [%s] [%s] [%d] %s";
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
                self::getFilename(),
                self::getLineNumber()
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
     * Output error message
     */
    public static function error($message, $options = array()) {
        $options['colour'] = 'red';
        $options['inputs'] = array(
            self::getTimestamp(),
            'error',
            self::getFilename(),
            self::getLineNumber()
        );
        self::log($message, $options);
    }

    /**
     * Output message
     */
    public static function out($message, $colour = false) {
        if($colour) {
            echo Colours::string($message, $colour).PHP_EOL;
        } else {
            echo $message.PHP_EOL;
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

    /**
     * Get linenumber of where the log is called from
     */
    private static function getLineNumber() {
        $bt = debug_backtrace();
        $file = $bt[1];
        if(isset($file['line'])) {
            return basename($file['line']);
        } else {
            return '';
        }
    }
}
