<?php
/**
 * Log test
 */
require_once "PHPUnit/Autoload.php";
require_once "lib/Log.php";

class LogTest extends PHPUnit_Framework_TestCase {
    /**
     * Test log
     */
    function testLog() {
        // Simple output
        Logger::log('Hello');
        $this->expectOutputString("[".date('Y-m-d H:i:s')."] [log] [LogTest.php] Hello\n");
    }

    /**
     * Test colour log
     */
    function testColourLog() {
        Logger::log('Hello', array('colour' => 'red'));
        // check for red output
        $string = "\033[0;31m";
        $string .= "[".date('Y-m-d H:i:s')."] [log] [LogTest.php] Hello";
        $string .= "\033[0m";
        $string .= "\n";
        $this->expectOutputString($string);
    }
}
