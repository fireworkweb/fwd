<?php

namespace App\Builder;

class DockerComposeRun extends DockerComposeAbstract
{
    public function getProgramName() : string
    {
        return 'run';
    }

    protected function getEnvVar() : string
    {
        return env('FWD_COMPOSE_RUN_FLAGS');
    }

    public function makeArgs(...$args) : array
    {
        return array_merge([
            '--rm',
            Unescaped::make(env('FWD_COMPOSE_RUN_FLAGS')),
        ], parent::makeArgs(...$args));
    }
}
