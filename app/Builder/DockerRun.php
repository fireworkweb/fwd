<?php

namespace App\Builder;

use App\Builder\Concerns\HasEnvironmentVariables;

class DockerRun extends Command
{
    use HasEnvironmentVariables;

    /** @var Docker $docker */
    protected $docker;

    public function __construct(...$args)
    {
        $this->docker = new Docker();

        $this->addEnv('ASUSER', Unescaped::make(env('FWD_ASUSER')));

        parent::__construct('run', ...[
            Argument::raw(env('FWD_DOCKER_RUN_FLAGS')),
            '--rm',
            new Argument('-w', '/app', ' '),
            new Argument('-v', sprintf('%s:/app:cached', env('FWD_CONTEXT_PATH')), ' '),
            new Argument('-v', sprintf('%s:/home/developer/.ssh/id_rsa:cached', env('FWD_SSH_KEY_PATH')), ' '),
        ] + $args);
    }

    public function __toString() : string
    {
        $this->parseEnvironmentToArgument();

        return $this->docker->appendArgument(Argument::raw(parent::__toString()))->__toString();
    }
}
