<?php

namespace App\Builder;

class RedisCli extends Command
{
    public function getProgramName()
    {
        return 'redis redis-cli';
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
