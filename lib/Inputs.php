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
        //$this->setOption($options['long'], $options);
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
        foreach($this->options as $option => $info) {
            // if option is in inputs
            $key = $this->checkInputs($info['short'], $info['long'], $this->inputs);
            if($key === false) {
                $this->pinputs[$info['short']] = false;
                $this->pinputs[$info['long']] = false;
            } else {
                // check if next input should be in input
        if(array_key_exists('input', $info) && $info['input'] == true) {
          $this->pinputs[$info['short']] = $this->inputs[$key + 1];
          $this->pinputs[$info['long']] = $this->inputs[$key + 1];
                    unset($this->inputs[$key]); // remove flag from inputs array
                    unset($this->inputs[$key + 1]);
                } else {
                    $this->pinputs[$info['short']] = true;
                    $this->pinputs[$info['long']] = true;
                    unset($this->inputs[$key]);
                }
            }
        }

        // loop through remaining flags and insert them at their indexes
        if(!empty($this->inputs)) {
            $this->inputs = array_values($this->inputs);
        }
        $this->pinputs =  array_merge($this->inputs, $this->pinputs);

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
     * Check input for flag
     */
    private function checkInputs($short, $long, $inputs) {
        $key = array_search($short, $this->inputs);
        if($key === false) {
            $key = array_search($long, $this->inputs);
            if($key === false) {
                return false;
            } else {
                return $key;
            }
        } else {
            return $key;
        }
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
                if($this->pinputs[$option['short']] == false) {
                    throw new Exception($option['help'].' is required');
                }
            }
        }
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
     * Get inputs
     */
    public function getInputs() {
        return $this->pinputs;
    }

    /**
     * Get Name
     */
    public function getName() {
        return $this->name;
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

    /**
     * Add a confirmation
     *
     * Returns true if yes, false if anything else
     */
    public static function confirm($msg) {
        echo $msg;
        $input = trim(fgets(STDIN));

        if(strtolower($input) == 'y' || strtolower($input) == 'yes') {
            return true;
        } else {
            return false;
        }
    }
}
