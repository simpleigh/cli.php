<?php
/**
 * CLI test
 */
require_once "PHPUnit/Autoload.php";
require_once "lib/Cli.php";

class CliTest extends PHPUnit_Framework_TestCase {

  /**
   * Test adding options
   */
  function testOption() {
    $cli = new Cli(array());
    $cli->option('-p, --peppers', 'Add peppers');
    $cli->option('-c, --cheese [type]', 'Add a cheese');

    $options = $cli->getOptions();

    $this->assertCount(4, $options);

    $this->assertArrayHasKey('p', $options);
    $this->assertArrayHasKey('c', $options);

    $this->assertArrayHasKey('peppers', $options);
    $this->assertArrayHasKey('cheese', $options);

    $this->assertTrue($options['cheese']['input']);
  }

  /**
   * Test parse
   */
  function testParse() {
    $cli = new Cli(array(
      'cli.php',
      '-p',
      '-c',
      'cheddar'
    ));
    $cli->option('-p, --peppers', 'Add peppers');
    $cli->option('-c, --cheese [type]', 'Add a cheese');

    $cli->parse();
  }
}
