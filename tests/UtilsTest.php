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
        $this->assertEquals("hello!", $output);
    }

    /**
     * Test exec fail
     * @expectedException Exception
     * @expectedExceptionMessage exit 1 failed with exit code 1
     */
    function testExecFail() {
        Utils::exec('exit 1');
    }

    /**
     * Test JSON decode
     */
    function testJSONDecode() {
        $json = '{"hello": "world"}';
        $output = Utils::jsonDecode($json);

        $this->assertArrayHasKey('hello', $output);
        $this->assertEquals('world', $output['hello']);
    }

    /**
     * Test JSON decode on malformed JSON
     * @expectedException Exception
     * @expectedExceptionMessage Syntax error, malformed JSON
     */
    function testJSONDecodeMalformed() {
        $json = '{hello: "world}';
        $output = Utils::jsonDecode($json);
    }

    /**
     * Test checkEnv 
     */
    function testCheckEnv() {
        putenv("FOO=BAR");
        Utils::checkEnv(array(
            'FOO'
        ));
    }

    /**
     * Test checkEnv when variable is not set
     * @expectedException Exception
     * @expectedExceptionMessage Variable BAR is not set
     */
    function testCheckEnvException() {
        Utils::checkEnv(array(
            'BAR'
        ));
    }

    /**
     * Test checkEnv when input is not an array
     * @expectedException Exception
     * @expectedExceptionMessage $variables is not an array
     */
    function testCheckEnvArray() {
        Utils::checkEnv('BAR');
    }

}
