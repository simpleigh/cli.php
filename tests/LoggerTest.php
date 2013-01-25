<?php
/**
 * Log test
 */
require_once "PHPUnit/Autoload.php";
require_once "lib/Logger.php";

class LogTest extends PHPUnit_Framework_TestCase {
    /**
     * Test log
     */
    function testLog() {
        // Simple output
        Logger::log('Hello');
        $this->expectOutputString("[".date('Y-m-d H:i:s')."] [log] [LoggerTest.php] [14] Hello\n");
    }

    /**
     * Test colour log
     */
    function testColourLog() {
        Logger::log('Hello', array('colour' => 'red'));
        // check for red output
        $string = "\033[0;31m";
        $string .= "[".date('Y-m-d H:i:s')."] [log] [LoggerTest.php] [22] Hello";
        $string .= "\033[0m";
        $string .= "\n";
        $this->expectOutputString($string);
    }

    /**
     * Test custom format
     */
    function testCustomFormat() {
        Logger::log('Hello', array(
            'format' => "[%s] [%s] %s",
            'inputs' => array(
                'log',
                'LoggerTest.php'
            )
        ));
        $this->expectOutputString("[log] [LoggerTest.php] Hello\n");
    }

    /**
     * Test out
     */
    function testOut() {
        Logger::out('Hi');
        $this->expectOutputString("Hi\n");
    }

    /**
     * Test out colour
     */
    function testOutColour() {
        Logger::out('Hi', 'red');
        $string = "\033[0;31m";
        $string .= "Hi";
        $string .= "\033[0m";
        $string .= "\n";
        $this->expectOutputString($string);
    }
}
