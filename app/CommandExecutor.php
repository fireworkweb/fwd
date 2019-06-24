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

    /** @var string $errorFileName */
    protected $errorFileName = '';

    public function __destruct() {
        if ($this->outputFileName) {
            unlink($this->outputFileName);
        }

        if ($this->errorFileName) {
            unlink($this->errorFileName);
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
            $this->print($this->getErrorBuffer());
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
        return $this->getFileContents($this->outputFileName);
    }

    public function getErrorBuffer(): string
    {
        return $this->getFileContents($this->errorFileName);
    }

    private function getFileContents(string $filename): string
    {
        $output = '';
        $handle = @fopen($filename, "r");

        while (($buffer = fgets($handle)) !== false) {
            $output .= trim($buffer) . PHP_EOL;
        }

        if (!feof($handle)) {
            $output = "Erro: falha inexperada na leitura do arquivo!";
        }

        return $output;
    }

    protected function getDescriptors() : array
    {
        if ($this->output || env('FWD_VERBOSE')) {
            return [STDIN, STDOUT, STDERR];
        }

        $this->outputFileName = @tempnam(sys_get_temp_dir(), 'fwd_output_');
        $this->errorFileName = @tempnam(sys_get_temp_dir(), 'fwd_error_');

        return [
            STDIN,
            [
                'file',
                $this->outputFileName,
                'w',
            ],
            [
                'file',
                $this->errorFileName,
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
