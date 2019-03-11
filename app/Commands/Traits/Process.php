<?php

namespace App\Commands\Traits;

use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process as SymfonyProcess;

trait Process
{
    public function getDefaultDockerCompose()
    {
        return base_path() . '/docker-compose.yml';
    }

    public function getContextDockerCompose()
    {
        return getcwd() . '/docker-compose.yml';
    }

    public function dockerRun(...$command)
    {
        $commandPrefix = [
            'docker run --rm -it',
            sprintf('-v %s:/app:cached', getcwd()),
            sprintf('-v %s:/home/developer/.ssh/id_rsa:cached', env('FWD_SSH_KEY_PATH')),
            sprintf('-e ASUSER=%s', env('FWD_ASUSER')),
        ];

        $this->process(array_merge($commandPrefix, $command));
    }

    public function dockerCompose(...$command)
    {
        $commandPrefix = [
            sprintf('docker-compose -p %s', basename(getcwd()))
        ];

        if (! File::exists($this->getContextDockerCompose())) {
            $commandPrefix[] = sprintf('-f %s', $this->getDefaultDockerCompose());
        }

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

        if (env('FWD_DEBUG')) {
            $this->line($command);
            return;
        }

        (new SymfonyProcess($command, $cwd, $env, $timeout))
            ->setTty(true)
            ->run($this->buildCallback($callback));
    }

    protected function buildCommand(array $command)
    {
        return implode(' ', array_filter($command));
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
