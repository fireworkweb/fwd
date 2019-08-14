<?php

namespace App\Builder;

class Composer extends Command
{
    public function getProgramName() : string
    {
        return 'app composer';
    }

    public function makeWrapper() : ?Command
    {
        return DockerComposeExec::make()->setUser(env('FWD_ASUSER'));
    }

    public function getDockerComposeExec() : DockerComposeExec
    {
        return $this->wrapper;
    }
}
