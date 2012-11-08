<?php
/**
 * Utils test
 */
require_once "PHPUnit/Autoload.php";
require_once "lib/Utils.php";

class UtilsTest extends PHPUnit_Framework_TestCase {
    /**
     * Test exec function
     */
    function testExec() {
        Utils::exec('echo hello!');
        $this->expectOutputString("hello!\n");
    }

    /**
     * Test exec with return var function
     */
    function testExecReturn() {
        $output = Utils::exec('echo hello!', true);
        $this->assertEquals("hello!\n", $output);
    }
}
