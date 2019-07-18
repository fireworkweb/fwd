<?php

namespace App\Builder;

use App\Builder\Concerns\HasEnvironmentVariables;

class Mysql extends Command
{
    use HasEnvironmentVariables;

    public function getProgramName()
    {
        return 'mysql mysql';
    }

    public function makeArgs(...$args) : array
    {
        return array_merge(['-u', 'root'], $args);
    }

    public function makeWrapper() : ?Command
    {
        return (new DockerComposeExec())
            ->addEnv('MYSQL_PWD', env('DB_PASSWORD'));
    }

    public function getDockerComposeExec() : DockerComposeExec
    {
        return $this->wrapper;
    }
}
