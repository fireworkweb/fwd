<?php

namespace App\Builder;

class DockerCompose extends Command
{
    public function __construct(...$args)
    {
        $this->setCommand(env('FWD_DOCKER_COMPOSE_BIN', 'docker-compose'));

        $this->appendArgument(new Argument('-p', Unescaped::make(env('FWD_NAME')), ' '));

        foreach ($args as $arg) {
            $this->addArgument($arg);
        }
    }
}
