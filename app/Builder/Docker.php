<?php

namespace App\Builder;

class Docker extends Command
{
    public function __construct(...$args)
    {
        $this->setCommand(env('FWD_DOCKER_BIN', 'docker'));

        foreach ($args as $arg) {
            $this->addArgument($arg);
        }
    }
}
