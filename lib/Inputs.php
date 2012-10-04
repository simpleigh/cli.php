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
    // remove name from script inputs
    $this->name = $inputs[0];
    unset($inputs[0]);
		if(!empty($inputs)) {
			$this->inputs = array_values($inputs);
		}
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
   *    $cli->get('-p'); // true/false
   *    $cli->get('-c'); // cheese type
   */
  public function option($flags, $help, $required = NULL) {
    $options = $this->parseOption($flags);

		$options['help'] = $flags.' '.$help;

		if($required) $options['required'] = true;

    $this->setOption($options['short'], $options);
    $this->setOption($options['long'], $options);
  }

  /**
   * Parse options
   */
  private function parseOption($string) {
    $output = array();
    $exploded = explode(',', $string);

    $output['short'] = $exploded[0]; // short flag

    $regex = '/\[(.*)\]/';
    $output['long'] = $exploded[1];
    if(preg_match($regex, $exploded[1])) { // check for input
      $output['long'] = preg_replace($regex, '', $exploded[1]); // replace input from string
      $output['input'] = true; // set input as true
    }
    $output['long'] = trim($output['long']);

    return $output;
  }

  /**
   * Parse
   * Process inputs
   */
  public function parse() {
		// loop through options and see if they are in the inputs
    // loop through inputs and compare them against options
    for($key = 0; $key < count($this->inputs); $key++) {
      $input = $this->inputs[$key];
      // check input is a flag and is in options
      if(
        $this->checkFlag($input)
        && array_key_exists(
          $flag = $input,
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

		// check required inputs
		try {
			$this->checkRequired();
		} catch(Exception $e) {
			echo $e->getMessage().PHP_EOL;
			return false;
		}

		return true;
  }

	/**
	 * Check required options
	 * If a required option is not provided then throw and exception
	 */
	private function checkRequired() {
		// Loop through all options
		foreach($this->options as $key => $option) {
			// if option is required
			if(array_key_exists('required', $option) && $option['required'] == true) {
				// check that it is defined in pinputs
				if(!array_key_exists($option['short'], $this->pinputs)) {
					throw new Exception($option['help'].' is required');
				}
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
      throw new Exception('Input '.$flag.' cannot be found');
    } else {
      return $this->pinputs[$flag];
    }
  }

	/**
	 * Prompt
	 * Add a new prompt
	 *
	 * $msg - message to display
	 * $password - is a password input or not (don't display text)
	 *
	 * N.B. Linux only
	 *
	 * Returns input from prompt
	 */
	public static function prompt($msg, $password = NULL) {
		// output message
		echo $msg;
		// if password then disable text output
		if($password != NULL) system('stty -echo');

		$input = trim(fgets(STDIN));

		if($password != NULL) {
			system('stty echo');
			echo PHP_EOL; // output end of line because the user's CR won't have been outputted
		}
		return $input;
	}
}
