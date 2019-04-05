<?php declare(strict_types=1);

namespace StudioIQ\Phake;

class OS {

    // Checks if the platform this script is running on is Windows
    public static function isWindows() : bool {
        switch (PHP_OS) {
            case 'WINNT':
            case 'WIN32':
            case 'Windows':
            case 'CYGWIN_NT-5.1':
                return true;
            default:
                return false;
            }
    }

    public static function execute(string $command) : int {
        $exitCode = 0;

        passthru($command, $exitCode);
        return $exitCode;
    }

    public static function executeSilently(string $command, string &$output = null, string &$error = null): int {
        $descriptorSpec = array(
            0 => array('pipe', 'r'), // STDIN
            1 => array('pipe', 'w'), // STDOUT
            2 => array('pipe', 'w')  // STDERR
        );

        try {
            $process = proc_open($command, $descriptorSpec, $pipes);

            if (is_resource($process)) {

                if ($output !== null) {
                    $output = stream_get_contents($pipes[1]);
                }

                if ($error !== null) {
                    $error = stream_get_contents($pipes[2]);
                }

                $exitCode = proc_close($process);
                return $exitCode;
            }
        } catch(Exception $e) {
            fail(sprintf("Failed to execute %s. Error = %s, Callstack = %s",
                $command,
                $e->getMessage(),
                $e->getTraceAsString()));
        }

        return -1;
    }
}
