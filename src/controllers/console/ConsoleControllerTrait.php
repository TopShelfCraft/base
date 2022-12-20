<?php
namespace TopShelfCraft\base\controllers\console;

use yii\console\ExitCode;
use yii\helpers\Console;

trait ConsoleControllerTrait
{

	abstract function stderr($string);
	abstract function stdout($string);

    /**
     * Writes a formatted error to the console
     */
    protected function writeErr($msg)
    {
        $this->stderr('Error: ', Console::BOLD, Console::FG_RED);
        $this->stderr(print_r($msg, true) . PHP_EOL);
    }

    /**
     * Writes a line to the console
     */
    protected function writeLine($msg)
    {
        $this->stdout(print_r($msg, true) . PHP_EOL);
    }

    /**
     * Runs the given function and returns an appropriate error code, writing out errors as needed.
     */
    protected function runAndExit(callable $function, $profile = false): int
    {

        $startTime = microtime(true);

        try
        {
            $function();
            $profile && $this->writeLine("(Completed in " . (microtime(true) - $startTime) . "s.)");
            return ExitCode::OK;
        }
        catch (\Exception $e)
        {
            $profile && $this->writeLine("(Failed after " . (microtime(true) - $startTime) . "s.)");
            $this->writeErr($e->getMessage());
            return ExitCode::UNSPECIFIED_ERROR;
        }

    }

}
