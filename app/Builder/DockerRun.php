<?php

namespace App\Builder;

use App\Builder\Concerns\HasEnvironmentVariables;

class DockerRun extends Command
{
    use HasEnvironmentVariables;

    public function getProgramName() : string
    {
        return 'run';
    }

    public function makeArgs(...$args) : array
    {
        return array_merge([
            env('FWD_DOCKER_RUN_FLAGS'),
            '--rm',
            new Argument('-w', '/app', ' '),
            new Argument('-v', sprintf('%s:/app:cached', env('FWD_CONTEXT_PATH')), ' '),
            new Argument('-v', sprintf('%s:/home/developer/.ssh/id_rsa:cached', env('FWD_SSH_KEY_PATH')), ' '),
        ], $args);
    }

    public function makeWrapper() : ?Command
    {
        return new Docker();
    }

    protected function build()
    {
        $this->addEnv('ASUSER', Unescaped::make(env('FWD_ASUSER')));

        $this->parseEnvironmentToArgument();

        return parent::build();
    }
}
