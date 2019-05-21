<?php

namespace App;

use App\Builder\Command;
use App\Events\BeforeExecuteCommand;

class CommandExecutor
{
    /** @var bool $output */
    protected $output = true;

    /** @var array $commands */
    protected $commands = [];

    public function enableOutput() : CommandExecutor
    {
        $this->output = true;

        return $this;
    }

    public function disableOutput() : CommandExecutor
    {
        $this->output = false;

        return $this;
    }

    public function noOutput(Command $command) : int
    {
        $this->disableOutput();

        $exitCode = $this->run($command);

        $this->enableOutput();

        return $exitCode;
    }

    public function run(Command $command) : int
    {
        event(new BeforeExecuteCommand($command));

        $shellCommand = $command->toString();

        if (env('FWD_DEBUG') || env('FWD_VERBOSE')) {
            $this->print($shellCommand);
        }

        if (env('FWD_DEBUG')) {
            return 0;
        }

        $this->commands[] = $shellCommand;

        return $this->execute($shellCommand, $command->getCwd());
    }

    public function commands() : array
    {
        return $this->commands;
    }

    public function hasCommand(string $command) : bool
    {
        return array_search($command, $this->commands) !== false;
    }

    public function execute(string $command, string $cwd) : int
    {
        $pipes = [];

        $proc = proc_open(
            $command,
            $this->getDescriptors(),
            $pipes,
            $cwd,
            null,
            []
        );

        return proc_close($proc);
    }

    protected function getDescriptors() : array
    {
        if ($this->output || env('FWD_VERBOSE')) {
            return [STDIN, STDOUT, STDERR];
        }

        $devNull = fopen('/dev/null', 'w');

        return [STDIN, $devNull, $devNull];
    }

    protected function print($line) : void
    {
        echo $line . PHP_EOL;
    }
}
