<?php
/**
 * Public: Utility class for useful static functions
 */
class Utils {
    /**
     * Public: Execute a shell command
     *
     * $cmd - command to execute
     * $return_content - capture output of command and return it
     *
     * Returns output of cmd if $return_content is set. Otherwise true
     *
     * Throws Exception if cmd fails
     */
    public static function exec($cmd, $return_content = false) {
        $return_var = 0;
        $output = true;

        // if return content then start output buffer to capture output
        if($return_content) ob_start();

        passthru($cmd, $return_var);

        if($return_content) $output = ob_get_contents();
        if($return_content) ob_end_clean();

        // if command exits with a code other than 0 throw exception
        if($return_var > 0) {
            throw new Exception($cmd.' failed with exit code '.$return_var);
        }

        return $output;
    }

    /**
     * Public: Decode JSON string and throw error if fails
     *
     * $string - JSON string to decode
     *
     * Returns associative array
     *
     * Throws Exception if json decode fails with message about why
     */
    public static function jsonDecode($string) {
        $json = json_decode($string, true);

        // if json_decode failed
        if($json == NULL) {
            switch (json_last_error()) {
                case JSON_ERROR_DEPTH:
                    throw new Exception('Maximum stack depth exceeded');
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    throw new Exception('Underflow or the modes mismatch');
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    throw new Exception('Unexpected control character found');
                    break;
                case JSON_ERROR_SYNTAX:
                    throw new Exception('Syntax error, malformed JSON');
                    break;
                case JSON_ERROR_UTF8:
                    throw new Exception('Malformed UTF-8 characters, possibly incorrectly encoded');
                    break;
                default:
                    throw new Exception('Unknown error');
                    break;
            }
        }

        return $json;
    }
}
