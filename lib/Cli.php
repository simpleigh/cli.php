<?php
/**
 * Cli class
 */
class Cli {
  protected $params = array();
  protected $options = array();
  protected $required = array();

  /**
   * Constructor
   *
   * @param params - array of input parameters
   */
  function __construct($params = NULL) {
    if($params == NULL && isset($argv)) $params = $argv;
    $this->params = $params;
  }

  /**
   * Add option
   *
   * $flags -
   * $help - help text
   * $required
   *
   * Example:
   *    $cli->option('-p, --peppers', 'Add peppers');
   *    $cli->option('-c, --cheese [type]', 'Add a cheese', true);
   *    $cli->get('p'); // true/false
   *    $cli->get('c'); // cheese type
   */
  public function option($flags, $help, $required = NULL) {
    $options = $this->parseOption($flags);
    $this->setOption($options['short'], $options);
    $this->setOption($options['long'], $options);
  }

  /**
   * Parse options
   */
  private function parseOption($string) {
    $output = array();
    $exploded = explode(',', $string);

    $output['short'] = substr($exploded[0], 1); // short flag

    $regex = '/\[(.*)\]/';
    $output['long'] = $exploded[1];
    if(preg_match($regex, $exploded[1])) { // check for input
      $output['long'] = preg_replace($regex, '', $exploded[1]); // replace input from string
      $output['input'] = true; // set input as true
    }
    $output['long'] = substr(trim($output['long']), 2);

    return $output;
  }

  /**
   * Get options
   */
  public function setOption($name, $options) {
    $this->options[$name] = $options;
  }

  /**
   * Get options
   */
  public function getOptions() {
    return $this->options;
  }
}
