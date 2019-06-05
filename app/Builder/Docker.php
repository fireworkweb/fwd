<?php

namespace App\Builder;

class Docker extends Command
{
    public function __construct(...$args)
    {
        parent::__construct(env('FWD_DOCKER_BIN', 'docker'), ...$args);
    }
}
