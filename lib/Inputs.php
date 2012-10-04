<?php
/**
 * Inputs class
 *
 * Parses command line inputs
 */
class Inputs {
  protected $options = array();
  protected $inputs = array();
  protected $pinputs = array(); // processed inputs
  protected $required = array();
  protected $name; // name of script

  /**
   * Constructor
   *
   * @param params - array of input parameters
   */
  function __construct($inputs = NULL) {
    if($inputs == NULL && isset($argv)) $inputs = $argv;

    // remove name from script inputs
    $this->name = $inputs[0];
    unset($inputs[0]);
    $this->inputs = array_values($inputs);
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
   * Parse
   * Process inputs
   */
  public function parse() {
    // loop through inputs and compare them against options
    for($key = 0; $key < count($this->inputs); $key++) {
      $input = $this->inputs[$key];
      // check input is a flag and is in options
      if(
        $this->checkFlag($input)
        && array_key_exists(
          $flag = $this->parseFlag($input),
          $this->options
        )
      ) {
        // check if next input should be in input
        if(array_key_exists('input', $this->options[$flag]) && $this->options[$flag]['input'] == true) {
          $this->pinputs[$this->options[$flag]['short']] = $this->inputs[$key + 1];
          $this->pinputs[$this->options[$flag]['long']] = $this->inputs[$key + 1];
          $key++;
          continue;
        } else { // just set flag as true
          $this->pinputs[$this->options[$flag]['short']] = true;
          $this->pinputs[$this->options[$flag]['long']] = true;
        }
      } else { // else just add input to parsed inputs
        $this->pinputs[] = $input;
      }
    }
  }

  /**
   * Check if input is a flag
   */
  private function checkFlag($string) {
    if(preg_match('/-\w|--\w+/', $string)) {
      return true;
    }
    return false;
  }

  /**
   * Parse flag
   */
  private function parseFlag($flag) {
    return preg_replace('/--|-/', '', $flag);
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

  /**
   * Get Name
   */
  public function getName() {
    return $this->name;
  }

  /**
   * Get inputs
   *
   * Get all inputs unmodified
   */
  public function getInputs() {
    return $this->inputs;
  }

  /**
   * Get input
   */
  public function get($flag) {
    if(!array_key_exists($flag, $this->pinputs)) {
      throw new Exception('Input cannot be found');
    } else {
      return $this->pinputs[$flag];
    }
  }
}
