<?php

namespace App\Builder;

class DockerCompose extends Command
{
    public function __construct(...$args)
    {
        parent::__construct(env('FWD_DOCKER_COMPOSE_BIN', 'docker-compose'), ...$args);

        $this->prependArgument(new Argument('-p', Unescaped::make(env('FWD_NAME')), ' '));
    }
}
