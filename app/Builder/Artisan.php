<?php

namespace App\Builder;

class Artisan extends Command
{
    public function __construct(...$args)
    {
        $this->setWrapper(new DockerComposeExec());

        $this->wrapper->setUser(env('FWD_ASUSER'));

        parent::__construct('app php artisan', ...$args);
    }

    public function getDockerComposeExec() : DockerComposeExec
    {
        return $this->wrapper;
    }
}
