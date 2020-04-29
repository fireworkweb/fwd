<?php

namespace App\Builder;

class DockerComposeExec extends DockerComposeAbstract
{
    public function getProgramName() : string
    {
        return 'exec';
    }

    public function makeArgs(...$args) : array
    {
        return array_merge([
            Unescaped::make(env('FWD_COMPOSE_EXEC_FLAGS')),
        ], parent::makeArgs(...$args));
    }
}
