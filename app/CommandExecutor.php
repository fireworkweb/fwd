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

    /** @var string $outputFileName */
    protected $outputFileName = '';

    public function __construct()
    {
        $this->outputFileName = @tempnam(sys_get_temp_dir(), 'fwd_output_');
    }

    public function __destruct()
    {
        if ($this->outputFileName) {
            unlink($this->outputFileName);
        }
    }

    public function enableOutput() : self
    {
        $this->output = true;

        return $this;
    }

    public function disableOutput() : self
    {
        $this->output = false;

        return $this;
    }

    public function runQuietly(Command $command) : int
    {
        $this->disableOutput();

        $exitCode = $this->run($command);

        $this->enableOutput();

        if ($exitCode) {
            $this->print($this->getOutputBuffer());
        }

        return $exitCode;
    }

    public function run(Command $command) : int
    {
        event(new BeforeExecuteCommand($command));

        $shellCommand = (string) $command;

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

    public function getOutputBuffer(): string
    {
        if (! $this->outputFileName) {
            return '';
        }

        $output = file_get_contents($this->outputFileName);

        if ($output === false) {
            return 'Error: Unexpected failure trying to read the output file!';
        }

        return trim($output);
    }

    protected function getDescriptors() : array
    {
        if ($this->output || env('FWD_VERBOSE')) {
            return [STDIN, STDOUT, STDERR];
        }

        $outputfile = $this->outputFileName ?: '/dev/null';

        return [
            STDIN,
            [
                'file',
                $outputfile,
                'w',
            ],
            [
                'file',
                $outputfile,
                'a',
            ],
        ];
    }

    protected function print($line) : void
    {
        if (empty($line)) {
            return;
        }

        echo $line . PHP_EOL;
    }
}
