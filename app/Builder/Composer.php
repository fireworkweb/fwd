<?php

namespace App\Builder;

use App\Builder\Concerns\HasWrapper;

class Composer extends Command
{
    use HasWrapper;

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
