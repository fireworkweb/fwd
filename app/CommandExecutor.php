<?php

namespace App;

use App\Builder\Builder;
use App\Events\BeforeExecuteCommand;

class CommandExecutor
{
    /** @var bool $output */
    protected $output = true;

    /** @var array $commands */
    protected $commands = [];

    /** @var resource $outputFile */
    protected $outputFile;

    /** @var string $outputBuffer */
    protected $outputBuffer = '';

    public function enableOutput() : self
    {
        $this->output = true;

        $this->setOutputBuffer($this->getOutputFileContents());

        $this->unsetOutputFile();

        return $this;
    }

    public function disableOutput() : self
    {
        $this->output = false;

        $this->prepareOutputFile();

        return $this;
    }

    public function runQuietly(Builder $command) : int
    {
        $this->disableOutput();

        $exitCode = $this->run($command);

        $this->enableOutput();

        if ($exitCode || env('FWD_VERBOSE')) {
            $this->print($this->getOutputBuffer());
        }

        return $exitCode;
    }

    public function run(Builder $builder) : int
    {
        $this->setOutputBuffer('');

        event(new BeforeExecuteCommand($builder));

        $command = (string) $builder;

        if (env('FWD_DEBUG') || env('FWD_VERBOSE')) {
            $this->print($command);
        }

        if (env('FWD_DEBUG')) {
            return 0;
        }

        $this->commands[] = $command;

        return $this->execute($command, $builder->getCwd());
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

    public function setOutputBuffer(string $outputBuffer): void
    {
        $this->outputBuffer = $outputBuffer;
    }

    public function getOutputBuffer(): string
    {
        return $this->outputBuffer;
    }

    protected function getDescriptors() : array
    {
        if ($this->output) {
            return [STDIN, STDOUT, STDERR];
        }

        return [STDIN, $this->outputFile, $this->outputFile];
    }

    public function print($line) : void
    {
        if (! empty($line)) {
            echo $line . PHP_EOL;
        }
    }

    protected function prepareOutputFile(): void
    {
        $filename = sprintf('%s/fwd_output_%s', sys_get_temp_dir(), uniqid());
        $this->outputFile = fopen($filename, 'w+') ?: fopen('/dev/null', 'w+');
    }

    protected function unsetOutputFile(): void
    {
        $filename = $this->getOutputFileName();

        fclose($this->outputFile);

        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    protected function getOutputFileContents()
    {
        $filename = $this->getOutputFileName();

        if (! file_exists($filename)) {
            return '';
        }

        $output = file_get_contents($filename);

        if ($output === false) {
            return "FWD Error: Unexpected failure trying to read the output file $filename.";
        }

        return trim($output);
    }

    protected function getOutputFileName(): string
    {
        $meta = stream_get_meta_data($this->outputFile);

        return $meta['uri'] ?? '';
    }
}
