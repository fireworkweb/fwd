<?php

namespace App\Builder;

use App\Builder\Concerns\HasEnvironmentVariables;

class Mysql extends Builder
{
    use HasEnvironmentVariables;

    public function getProgramName() : string
    {
        return 'mysql mysql';
    }

    public function makeArgs(...$args) : array
    {
        return array_merge(
            ['-u', 'root'],
            parent::makeArgs(...$args)
        );
    }

    public function makeWrapper() : ?Builder
    {
        return (new DockerComposeExec())
            ->addEnv('MYSQL_PWD', env('DB_PASSWORD'));
    }

    public function getDockerComposeExec() : DockerComposeExec
    {
        return $this->wrapper;
    }
}
