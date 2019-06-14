<?php

namespace App\Builder;

use App\Builder\Concerns\HasEnvironmentVariables;

class DockerComposeExec extends Command
{
    use HasEnvironmentVariables;

    /** @var string $user */
    protected $user = '';

    public function getProgramName()
    {
        return 'exec';
    }

    public function makeWrapper() : ?Command
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

    protected function beforeBuild(Command $command) : Command
    {
        $command->parseEnvironmentToArgument();

        if ($user = $command->getUser()) {
            $command->prependArgument(
                new Argument('--user', Unescaped::make($user), ' ')
            );
        }

        $command->prependArgument(new Argument(
            Unescaped::make(env('FWD_COMPOSE_EXEC_FLAGS'))
        ));

        return $command;
    }
}
