<?php declare(strict_types=1);

namespace StudioIQ\Phake;

class Terminal {

    // Prompts before performing an action and exits if the user doesn't confirm
    public static function confirm(string $prompt) {
        echo $prompt . PHP_EOL;

        echo "Are you sure you want to do this?  Type 'yes' to continue: ";
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);

        if(trim($line) != 'yes'){
            echo "ABORTING!" . PHP_EOL;
            exit;
        }

        fclose($handle);
        echo PHP_EOL . "Thank you, continuing..." . PHP_EOL;
    }

    // Prints $message to Terminal in bold text, followed by an optional newline
    public static function shout(string $message, bool $newline = true) {
        echo "\033[1m{$message}\033[0m" . ($message ? "\n" : '');
    }

    // Prints $message to Terminal in bold green text, followed by an optional newline
    public static function success(string $message = 'Done!', bool $newline = true, float $startTime = null) {
        if ($startTime != null) {
            $timeTaken = microtime(true) - $startTime;
        }

        echo "\033[1;32m{$message}\033[0m" . ($startTime != null ? sprintf(' (took %0.2fs)', $timeTaken) : '') . ($newline ? "\n" : '');
    }

    // Prints $string to Terminal in bold yellow text, followed by an optional newline
    public static function warn(string $message, bool $newline = true) {
        echo "\033[1;33m{$message}\033[0m" . ($newline ? "\n" : '');
    }

    // Prints $string to Terminal in bold red text, followed by an optional newline
    public static function fail(string $message, bool $newline = true) {
        echo "\033[1;31m{$message}\033[0m" . ($newline ? "\n" : '');
    }
}
