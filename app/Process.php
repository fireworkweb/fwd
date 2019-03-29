<?php

namespace App;

use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process as SymfonyProcess;

class Process
{
    protected $commands = [];

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
            sprintf('docker-compose -p %s', env('FWD_NAME', basename(getcwd()))),
        ];

        // @TODO: make docker-compose.yml optional
        // if (! File::exists($environment->getContextDockerCompose())) {
        //     $commandPrefix[] = sprintf('-f %s', $environment->getDefaultDockerCompose());
        // }

        $this->process(array_merge($commandPrefix, $command));
    }

    public function process(
        array $command,
        string $cwd = null,
        array $env = [],
        $timeout = 0,
        $callback = null
    ) {
        $command = $this->buildCommand($command);

        $this->commands[] = $command;

        return env('FWD_DEBUG')
            ? print("$command\n")
            : $this->run($command, $cwd, $env, $timeout, $callback);
    }

    public function getCommands()
    {
        return $this->commands;
    }

    public function hasCommand($command)
    {
        return array_search($command, $this->commands) !== false;
    }

    protected function buildCommand(array $command)
    {
        return implode(' ', array_filter($command));
    }

    protected function run(
        string $command,
        string $cwd = null,
        array $env = [],
        $timeout = 0,
        $callback = null
    ) {
        return (new SymfonyProcess($command, $cwd, $env, $timeout))
            ->setTty(true)
            ->run($this->buildCallback($callback));
    }

    protected function buildCallback($callback)
    {
        return $callback ?: function ($type, $buffer) {
            $buffer = trim($buffer);

            switch ($type) {
                case 'err':
                    $this->warn($buffer);
                    break;

                case 'out':
                    $this->line($buffer);
                    break;
            }
        };
    }
}
