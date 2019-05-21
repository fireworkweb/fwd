<?php

namespace App\Builder;

class DockerComposeExec extends Command
{
    /** @var Command $dockerCompose */
    protected $dockerCompose;

    /** @var array $environment */
    protected $environment = [];

    /** @var string $user */
    protected $user;

    public function __construct(...$args)
    {
        $this->dockerCompose = new DockerCompose();

        parent::__construct('exec', ...$args);
    }

    public function getDockerCompose() : DockerCompose
    {
        return $this->dockerCompose;
    }

    public function addEnv($var, $value = null) : DockerComposeExec
    {
        $this->appendEnv(new Argument($var, $value));

        return $this;
    }

    public function appendEnv(Argument $env) : DockerComposeExec
    {
        $this->environment[] = $env;

        return $this;
    }

    public function setUser(string $user) : DockerComposeExec
    {
        $this->user = $user;

        return $this;
    }

    public function __toString() : string
    {
        foreach ($this->environment as $envArg) {
            $this->args = array_prepend($this->args, new Argument('-e', $envArg, ' '));
        }

        if ($this->user) {
            $this->args = array_prepend($this->args, new Argument('--user', Unescaped::make($this->user), ' '));
        }

        // env('FWD_COMPOSE_EXEC_FLAGS')

        return $this->dockerCompose->addArgument(Unescaped::make(parent::__toString()))->__toString();
    }
}
