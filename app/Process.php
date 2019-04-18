<?php

namespace App;

class Process
{
    protected $commands = [];
    protected $cwd = null;

    public function dockerRun(...$command) : int
    {
        $commandPrefix = [
            'docker run --rm',
            env('FWD_DOCKER_RUN_FLAGS'),
            '-w /app',
            sprintf('-v %s:/app:cached', env('FWD_CONTEXT_PATH')),
            sprintf('-v %s:/home/developer/.ssh/id_rsa:cached', env('FWD_SSH_KEY_PATH')),
            sprintf('-e ASUSER=%s', env('FWD_ASUSER')),
        ];

        return $this->process(array_merge($commandPrefix, $command));
    }

    public function dockerComposeExec(...$command)
    {
        $this->dockerCompose('exec', env('FWD_COMPOSE_EXEC_FLAGS'), ...$command);
    }

    public function dockerCompose(...$command) : int
    {
        $commandPrefix = [
            sprintf('docker-compose -p %s', env('FWD_NAME')),
        ];

        // @TODO: make docker-compose.yml optional
        // $environment = app(Environment::class);
        // if (! File::exists($environment->getContextDockerCompose())) {
        //     $commandPrefix[] = sprintf('-f %s', $environment->getDefaultDockerCompose());
        // }

        return $this->process(array_merge($commandPrefix, $command));
    }

    public function process(array $command, string $cwd = null) : int
    {
        $command = $this->buildCommand($command);

        $this->commands[] = $command;

        if (env('FWD_DEBUG')) {
            $this->print($command);

            return 0;
        }

        return $this->run($command, $cwd);
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
        return trim(implode(' ', array_filter($command)));
    }

    protected function run(string $command) : int
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
