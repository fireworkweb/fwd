<?php

namespace App\Builder;

class Docker extends Command
{
    public function getProgramName()
    {
        return env('FWD_DOCKER_BIN', 'docker');
    }
}
