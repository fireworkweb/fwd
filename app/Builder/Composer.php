<?php

namespace App\Builder;

class Composer extends Command
{
    public function getProgramName()
    {
        return 'app composer';
    }

    public function makeWrapper() : ?Command
    {
        return (new DockerComposeExec())->setUser(env('FWD_ASUSER'));
    }

    public function getDockerComposeExec() : DockerComposeExec
    {
        return $this->wrapper;
    }
}
