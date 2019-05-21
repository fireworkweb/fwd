<?php

namespace App\Builder;

class Artisan extends Command
{
    /** @var Command $exec */
    protected $exec;

    public function __construct(...$args)
    {
        $this->exec = new DockerComposeExec();

        $this->exec->setUser(env('FWD_ASUSER'));

        parent::__construct('app php artisan', ...$args);
    }

    public function getDockerComposeExec() : DockerComposeExec
    {
        return $this->exec;
    }

    public function __toString() : string
    {
        return $this->exec->addArgument(Unescaped::make(parent::__toString()))->__toString();
    }
}
