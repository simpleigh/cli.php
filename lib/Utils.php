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
}
