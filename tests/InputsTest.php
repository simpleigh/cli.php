<?php
/**
 * Inputs test
 */
require_once "PHPUnit/Autoload.php";
require_once "lib/Inputs.php";

class InputsTest extends PHPUnit_Framework_TestCase {
    /**
     * Test adding options
     */
    function testOption() {
        $cli = new Inputs(array('cli.php'));
        $cli->option('-p, --peppers', 'Add peppers');
        $cli->option('-c, --cheese [type]', 'Add a cheese');

        $options = $cli->getOptions();

        $this->assertCount(2, $options);

        $this->assertArrayHasKey('-p', $options);
        $this->assertArrayHasKey('-c', $options);

        $this->assertTrue($options['-c']['input']);
    }

    /**
     * Test get name
     */
    function testGetName() {
        $cli = new Inputs(array(
          'cli.php',
          '-p'
        ));

        $this->assertEquals('cli.php', $cli->getName());

        $cli->parse();

        $inputs = $cli->getInputs();
        $this->assertEquals('-p', $inputs[0]);
    }

    /**
     * Test parse
     */
    function testParse() {
        $cli = new Inputs(array(
          'cli.php',
          '-p',
          '--cheese',
          'cheddar'
        ));
        $cli->option('-p, --peppers', 'Add peppers');
        $cli->option('-c, --cheese [type]', 'Add a cheese');
        $cli->option('-m, --mayo', 'Add mayonaise');

        $cli->parse();

        $this->assertTrue($cli->get('-p'));
        $this->assertTrue($cli->get('--peppers'));

        $this->assertEquals('cheddar', $cli->get('-c'));
        $this->assertEquals('cheddar', $cli->get('--cheese'));

        $this->assertFalse($cli->get('-m'));
        $this->assertFalse($cli->get('--mayo'));
    }

    /**
     * Test parsing non options
     */
    function testParsingNonOptions() {
        $cli = new Inputs(array(
          'cli.php',
          '-p',
          '--cheese',
          'cheddar',
          'extra',
          '-b',
          'info'
        ));
        $cli->option('-p, --peppers', 'Add peppers');
        $cli->option('-c, --cheese [type]', 'Add a cheese');

        $cli->parse();

        $this->assertTrue($cli->get('-p'));
        $this->assertTrue($cli->get('--peppers'));

        $this->assertEquals('cheddar', $cli->get('-c'));
        $this->assertEquals('cheddar', $cli->get('--cheese'));

        $this->assertEquals('extra', $cli->get(0));
        $this->assertEquals('-b', $cli->get(1));
        $this->assertEquals('info', $cli->get(2));
    }

    /**
     * Test required
     */
    function testRequired() {
        $cli = new Inputs(array(
          'cli.php',
          '-p'
        ));
        $cli->option('-h, --ham', 'Add ham');
        $cli->option('-b, --bread [type]', 'Type of bread', true);

        // expect parse to throw an exception that input is not defined
        $this->expectOutputString("-b, --bread [type] Type of bread is required\n");

        $cli->parse();
    }

    /**
     * Test params
     */
    function testParams() {
        $cli = new Inputs(array(
          'cli.php',
          'test',
          'uk'
        ));
        $cli->param('client', 'Name of client', true);
        $cli->param('locale', 'Client locale');
        $cli->parse();

        // expect parse to throw an exception that input is not defined
        $this->assertEquals("test", $cli->get('client'));
        $this->assertEquals("uk", $cli->get('locale'));
    }

    /**
     * Test required
     */
    function testRequiredParam() {
        $cli = new Inputs(array(
          'cli.php'
        ));

        $cli->param('client', 'Specify client', true);

        // expect parse to throw an exception that input is not defined
        $this->expectOutputString("\"<client> Specify client\" is required\n");

        $cli->parse();
    }

    /**
     * Test help text
     */
    function testHelp() {
        $cli = new Inputs(array(
            'cli.php',
            '-p',
            '--help'
        ));

        $cli->option('-p, --peppers', 'Add peppers');
        $cli->option('-c, --cheese [type]', 'Add a cheese');
        $cli->option('-m, --mayo', 'Add mayonaise');
        $cli->option('-b, --bread [type]', 'Type of bread', true);

        $cli->param('client', 'Name of client', true);
        $cli->param('locale', 'Client locale');

        $cli->parse();

        $this->expectOutputString("Usage: cli.php <client> [locale] [options]\n\nParameters:\n\t<client> Name of client\n\t[locale] Client locale\n\nOptions:\n\t-p, --peppers Add peppers\n\t-c, --cheese [type] Add a cheese\n\t-m, --mayo Add mayonaise\n\t-b, --bread [type] Type of bread [required]\n\t-h, --help Output usage information\n");
    }
}
