<?php

namespace App\Builder;

use App\Builder\Concerns\HasEnvironmentVariables;

class DockerComposeExec extends Builder
{
    use HasEnvironmentVariables;

    /** @var string $user */
    protected $user = '';

    public function getProgramName() : string
    {
        return 'exec';
    }

    public function makeWrapper() : ?Builder
    {
        return new DockerCompose();
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

    public function getUser() : string
    {
        return $this->user;
    }

    protected function getEnvVar() : string
    {
        return env('FWD_COMPOSE_EXEC_FLAGS');
    }

    protected function beforeBuild(Builder $command) : Builder
    {
        $command->parseEnvironmentToArgument();

        if ($user = $command->getUser()) {
            $command->prependArgument(
                new Argument('--user', Unescaped::make($user), ' ')
            );
        }

        $command->prependArgument(new Argument(
            Unescaped::make($this->getEnvVar())
        ));

        return $command;
    }
}
