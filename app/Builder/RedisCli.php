<?php

namespace App\Builder;

class RedisCli extends Builder
{
    public function getProgramName() : string
    {
        return 'cache redis-cli';
    }

    public function makeWrapper() : ?Builder
    {
        return (new DockerComposeExec())->setUser(env('FWD_ASUSER'));
    }

    public function getDockerComposeExec() : DockerComposeExec
    {
        return $this->wrapper;
    }
}
