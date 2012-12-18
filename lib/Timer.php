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
    protected $avgs = array(); // avg array

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
        $this->blocks[$block]['start-line'] = $this->getLineNumber();
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
        $this->blocks[$block]['stop-line'] = $this->getLineNumber();
    }

    /**
     * Start avg block
     *
     * $block - identifier
     */
    public function startAvg($block) {
        if(!isset($this->avgs[$block])) {
            $this->avgs[$block] = array();
            $this->avgs[$block]['count'] = 0;
            $this->avgs[$block]['total'] = 0;
        }

        $this->avgs[$block]['start'] = microtime(true);
        if(!isset($this->avgs[$block]['start-line'])) {
            $this->avgs[$block]['start-line'] = $this->getLineNumber();
        }
    }

    /**
     * Stop avg block and calculate average
     *
     * $block
     */
    public function stopAvg($block) {
        if(!isset($this->avgs[$block])) {
            throw new Exception('Average block '.$block.' has not been started!');
        }

        $this->avgs[$block]['stop'] = microtime(true);
        if(!isset($this->avgs[$block]['stop-line'])) {
            $this->avgs[$block]['stop-line'] = $this->getLineNumber();
        }

        // Calculate average
        $this->avgs[$block]['count']++; // increment count

        $time = $this->avgs[$block]['stop'] - $this->avgs[$block]['start'];
        $this->avgs[$block]['total'] = $this->avgs[$block]['total'] + $time;
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

            echo PHP_EOL;
            echo 'Averages:'.PHP_EOL;
            foreach($this->avgs as $key => $block) {
                $this->printAvgBlock($key);
            }
        } else {
            try {
                $this->printBlock($block);
            } catch (Exception $e) {
                try {
                    $this->printAvgBlock($block);
                } catch (Exception $e) {
                    throw new Exception('Block does not exist in either average or normal blocks');
                }
            }
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
        $this->finishBlock($block);

        echo "    $block";
        echo " (".$this->blocks[$block]['start-line']."-".$this->blocks[$block]['stop-line'].")";
        echo ": ";
        echo round($this->blocks[$block]['total'], 4);
        echo ' seconds';
        echo PHP_EOL;
    }

    /**
     * Private: Print average block
     */
    private function printAvgBlock($block) {
        if(!array_key_exists($block, $this->avgs)) {
            throw new Exception('Average block '.$blocks.' not defined');
        }
        $this->finishAvgBlock($block);

        echo "    $block";
        echo " [".$this->avgs[$block]['count']."]";
        echo " (".$this->avgs[$block]['start-line']."-".$this->avgs[$block]['stop-line'].")";
        echo ": ";
        echo round($this->avgs[$block]['avg'], 4);
        echo ' seconds';
        echo PHP_EOL;
    }

    /**
     * Private: Get line number where command was called from
     */
    private function getLineNumber() {
        $bg = debug_backtrace();
        return $bg[1]['line'];
    }

    /**
     * Public: get block info
     *
     * $block - identifier
     *
     * Returns associative array
     */
    public function get($block) {
        if(!array_key_exists($block, $this->blocks)) {
            throw new Exception('Block '.$blocks.' not defined');
        }

        $this->finishBlock($block);

        return $this->blocks[$block];
    }

    /**
     * Public: get average block info
     *
     * $block - identifier
     *
     * Returns associative array
     */
    public function getAvg($block) {
        if(!array_key_exists($block, $this->avgs)) {
            throw new Exception('Average block '.$blocks.' not defined');
        }

        $this->finishAvgBlock($block);

        return $this->avgs[$block];
    }

    /**
     * Private: Finish block
     * 
     * $block - identifier
     */
    private function finishBlock($block) {
        if(!array_key_exists($block, $this->blocks)) {
            throw new Exception('Block '.$blocks.' not defined');
        }
        $this->blocks[$block]['total'] = $this->blocks[$block]['stop'] - $this->blocks[$block]['start'];

        return $this->blocks[$block];
    }

    /**
     * Private: Finish average block
     * 
     * $block - identifier
     */
    private function finishAvgBlock($block) {
        if(!array_key_exists($block, $this->avgs)) {
            throw new Exception('Average block '.$blocks.' not defined');
        }
        $this->avgs[$block]['avg'] = $this->avgs[$block]['total'] / $this->avgs[$block]['count'];

        return $this->avgs[$block];
    }
}
