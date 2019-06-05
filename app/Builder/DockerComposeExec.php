<?php

namespace App\Builder;

use App\Builder\Concerns\HasEnvironmentVariables;

class DockerComposeExec extends Command
{
    use HasEnvironmentVariables;

    /** @var Command $dockerCompose */
    protected $dockerCompose;

    /** @var string $user */
    protected $user;

    public function __construct(...$args)
    {
        $this->dockerCompose = new DockerCompose();

        parent::__construct('exec', ...[
            Argument::raw(env('FWD_COMPOSE_EXEC_FLAGS')),
        ] + $args);
    }

    public function getDockerCompose() : DockerCompose
    {
        return $this->dockerCompose;
    }

    public function setUser(string $user) : DockerComposeExec
    {
        $this->user = $user;

        return $this;
    }

    public function __toString() : string
    {
        $this->parseEnvironmentToArgument();

        if ($this->user) {
            $this->args->prepend(new Argument('--user', Unescaped::make($this->user), ' '));
        }

        return $this->dockerCompose->addArgument(Unescaped::make(parent::__toString()))->__toString();
    }
}
