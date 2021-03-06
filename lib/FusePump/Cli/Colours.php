<?php

namespace FusePump\Cli;

/**
 * CLI colours
 */
class Colours
{
    private static $foreground_colours = array(
        'black' => '0;30',
        'dark_gray' => '1;30',
        'red' => '0;31',
        'bold_red' => '1;31',
        'green' => '0;32',
        'bold_green' => '1;32',
        'brown' => '0;33',
        'yellow' => '1;33',
        'blue' => '0;34',
        'bold_blue' => '1;34',
        'purple' => '0;35',
        'bold_purple' => '1;35',
        'cyan' => '0;36',
        'bold_cyan' => '1;36',
        'white' => '1;37',
        'bold_gray' => '0;37'
    );

    private static $background_colours = array(
        'black' => '40',
        'red' => '41',
        'magenta' => '45',
        'yellow' => '43',
        'green' => '42',
        'blue' => '44',
        'cyan' => '46',
        'light_gray' => '47'
    );

    // Returns coloured string
    public static function string($string, $foreground_colour = null, $background_colour = null)
    {
        $coloured_string = "";

        // Check if given foreground colour found
        if (isset(self::$foreground_colours[$foreground_colour])) {
            $coloured_string .= "\033[" . self::$foreground_colours[$foreground_colour] . "m";
        }
        // Check if given background colour found
        if (isset(self::$background_colours[$background_colour])) {
            $coloured_string .= "\033[" . self::$background_colours[$background_colour] . "m";
        }

        // Add string and end colouring
        $coloured_string .=  $string . "\033[0m";

        return $coloured_string;
    }

    // Returns all foreground colour names
    public static function getForegroundColours()
    {
        return array_keys(self::$foreground_colours);
    }

    // Returns all background colour names
    public static function getBackgroundColours()
    {
        return array_keys(self::$background_colours);
    }
}
