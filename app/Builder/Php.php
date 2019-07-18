<?php

namespace App\Builder;

class Php extends Command
{
    public function getProgramName()
    {
        return 'app php';
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
