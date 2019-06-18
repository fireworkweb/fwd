<?php

namespace App\Builder;

class DockerCompose extends Command
{
    public function getProgramName()
    {
        return env('FWD_DOCKER_COMPOSE_BIN', 'docker-compose');
    }

    public function makeArgs(...$args) : array
    {
        return array_merge([
            '-p',
            env('FWD_NAME'),
        ], $args);
    }
}
