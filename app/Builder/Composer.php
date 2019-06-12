<?php

namespace App\Builder;

class Composer extends Command
{
    public function __construct(...$args)
    {
        $this->setWrapper(new DockerComposeExec());

        $this->wrapper->setUser(env('FWD_ASUSER'));

        parent::__construct('app composer', ...$args);
    }

    public function getDockerComposeExec() : DockerComposeExec
    {
        return $this->wrapper;
    }
}
