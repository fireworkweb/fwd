<?php

namespace App\Builder;

use App\Builder\Concerns\HasEnvironmentVariables;

class DockerComposeExec extends Command
{
    use HasEnvironmentVariables;

    /** @var string $user */
    protected $user;

    public function __construct(...$args)
    {
        $this->setWrapper(new DockerCompose());

        parent::__construct('exec', ...$args);
    }

    public function getDockerCompose() : DockerCompose
    {
        return $this->wrapper;
    }

    public function setUser(string $user) : self
    {
        $this->user = $user;

        return $this;
    }

    protected function build()
    {
        $this->parseEnvironmentToArgument();

        if ($this->user) {
            $this->args->prepend(new Argument('--user', Unescaped::make($this->user), ' '));
        }

        $this->args->prepend(env('FWD_COMPOSE_EXEC_FLAGS'));

        return parent::build();
    }
}
