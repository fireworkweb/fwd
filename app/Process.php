<?php

namespace App;

use Illuminate\Support\Facades\File;

class Process
{
    protected $commands = [];
    protected $cwd = null;

    public function dockerRun(...$command)
    {
        $commandPrefix = [
            'docker run --rm -it',
            sprintf('-v %s:/app:cached', env('FWD_CONTEXT_PATH')),
            sprintf('-v %s:/home/developer/.ssh/id_rsa:cached', env('FWD_SSH_KEY_PATH')),
            sprintf('-e ASUSER=%s', env('FWD_ASUSER')),
        ];

        $this->process(array_merge($commandPrefix, $command));
    }

    public function dockerCompose(...$command)
    {
        $environment = app(Environment::class);
        $commandPrefix = [
            sprintf('docker-compose -p %s', env('FWD_NAME')),
        ];

        // @TODO: make docker-compose.yml optional
        // if (! File::exists($environment->getContextDockerCompose())) {
        //     $commandPrefix[] = sprintf('-f %s', $environment->getDefaultDockerCompose());
        // }

        $this->process(array_merge($commandPrefix, $command));
    }

    public function process(array $command, string $cwd = null)
    {
        $command = $this->buildCommand($command);

        $this->commands[] = $command;

        return env('FWD_DEBUG')
            ? $this->print($command)
            : $this->run($command, $cwd);
    }

    public function commands()
    {
        return $this->commands;
    }

    public function cwd(string $cwd)
    {
        return $this->cwd = $cwd;

        return $this;
    }

    public function hasCommand($command)
    {
        return array_search($command, $this->commands) !== false;
    }

    protected function buildCommand(array $command)
    {
        return implode(' ', array_filter($command));
    }

    protected function run(string $command)
    {
        $pipes = [];
        $proc = proc_open(
            $command,
            [STDIN, STDOUT, STDERR],
            $pipes,
            $this->cwd,
            null,
            []
        );
        return proc_close($proc);
    }

    protected function getCallback()
    {
        return $this->callback ?: function ($type, $buffer) {
            $buffer = trim($buffer);

            switch ($type) {
                case 'err':
                    $this->print($buffer);
                    break;

                case 'out':
                    $this->print($buffer);
                    break;
            }
        };
    }

    protected function print($line)
    {
        echo $line . PHP_EOL;
    }
}
