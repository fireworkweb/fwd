<?php

namespace App;

use App\Builder\Command;
use Illuminate\Support\Carbon;
use App\Events\BeforeExecuteCommand;

class CommandExecutor
{
    /** @var bool $output */
    protected $output = true;

    /** @var array $commands */
    protected $commands = [];

    /** @var resource $outputFile */
    protected $outputFile;

    public function __construct()
    {
        $filename = rtrim(sys_get_temp_dir() . '/fwd_output_' . Carbon::now()->format('Ymdhis'));
        $this->outputFile = fopen($filename, 'w+') ?: fopen('/dev/null', 'w+');
    }

    public function __destruct()
    {
        if (is_resource($this->outputFile)) {
            fclose($this->outputFile);
        }

        $filename = $this->getOutputFileName();
        if (file_exists($filename)) {
            unlink($filename);
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
        $filename = $this->getOutputFileName();

        if (! file_exists($filename)) {
            return '';
        }

        $output = file_get_contents($filename);

        if ($output === false) {
            return "Error: Unexpected failure trying to read the output file $filename.";
        }

        return trim($output);
    }

    protected function getDescriptors() : array
    {
        if ($this->output || env('FWD_VERBOSE')) {
            return [STDIN, STDOUT, STDERR];
        }

        return [STDIN, $this->outputFile, $this->outputFile];
    }

    protected function print($line) : void
    {
        if (empty($line)) {
            return;
        }

        echo $line . PHP_EOL;
    }

    protected function getOutputFileName(): string
    {
        if (! is_resource($this->outputFile)) {
            return '';
        }

        $meta = stream_get_meta_data($this->outputFile);

        return $meta['uri'] ?? '';
    }
}
