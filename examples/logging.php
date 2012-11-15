<?php
/**
 * Logging examples
 */

require dirname(__FILE__)."/../lib/Logger.php";

Logger::log('Hello!'); // => [2012-11-15 18:12:34] [log] [logging.php] Hello!
Logger::log('This is red!', array('colour' => 'red')); // => [2012-11-15 18:12:34] [log] [logging.php] This is red!
Logger::log('This is green!', array('colour' => 'green')); // => [2012-11-15 18:12:34] [log] [logging.php] This is green!

Logger::log('Custom formatting options!', array('format' => '[%s] %s', 'inputs' => array('custom_log'))); // [Custom log] Custom formatting options!

