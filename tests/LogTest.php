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
        $this->expectOutputString("Hello\n");
    }

    /**
     * Test colour log
     */
    function testColourLog() {
        Logger::log('Hello', array('colour' => 'red'));
        // check for red output
        $this->expectOutputString("\033[0;31mHello\033[0m\n");
    }
}
