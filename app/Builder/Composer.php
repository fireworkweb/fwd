<?php

namespace App\Builder;

class Composer extends Builder
{
    public function getProgramName() : string
    {
        return 'app composer';
    }

    public function makeWrapper() : ?Builder
    {
        return DockerComposeExec::make()->setUser(env('FWD_ASUSER'));
    }

    public function getDockerComposeExec() : DockerComposeExec
    {
        return $this->wrapper;
    }
}
