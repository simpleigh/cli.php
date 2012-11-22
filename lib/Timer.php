<?php
/**
 * Timer class
 *
 * Usage:
 *  $clock = new Timer();
 *  $clock->start('block1');
 *  sleep(10);
 *  $clock->stop('block1');
 *
 *  $clock->start('block2');
 *  sleep(15);
 *  $clock->stop('block2');
 *
 *  $clock->report(); 
 *  // block1: 10 seconds
 *  // block2: 15 seconds
 *
 */
class Timer {
    protected $blocks = array(); // array to store timing blocks

    /**
     * Constructor
     */
    function __construct() {

    }
    
    /**
     * Start function
     *
     * $block - key to identify block
     */
    public function start($block) {
        if(!isset($this->blocks[$block])) {
            $this->blocks[$block] = array();
        }

        $this->blocks[$block]['start'] = microtime(true);
    }

    /**
     * Stop function
     *
     * $block - key to identify block
     */
    public function stop($block) {
        if(!isset($this->blocks[$block])) {
            throw new Exception('Block '.$block.' has not been started!');
        }

        $this->blocks[$block]['stop'] = microtime(true);
    }

    /**
     * Print function
     *
     * $block - optionally specify which block to print
     */
    public function report($block = NULL) {
        echo 'Timing report:'.PHP_EOL;
        if($block === NULL) {
            foreach($this->blocks as $key => $block) {
                $this->printBlock($key);
            }
        } else {
            $this->printBlock($block);
        }
        echo PHP_EOL;
    }

    /**
     * Private: Print block
     */
    private function printBlock($block) {
        if(!array_key_exists($block, $this->blocks)) {
            throw new Exception('Block '.$blocks.' not defined');
        }
        echo "    $block: ";
        $time = $this->blocks[$block]['stop'] - $this->blocks[$block]['start'];
        echo round($time, 4);
        echo ' seconds';
        echo PHP_EOL;
    }
}
