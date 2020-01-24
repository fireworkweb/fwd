<?php

namespace App\Builder;

class Php extends Builder
{
    public function getProgramName() : string
    {
        $phpService = env('FWD_PHP_SERVICE');

        return "${phpService} php";
    }

    public function makeWrapper() : ?Builder
    {
        return DockerComposeExec::make()->setUser(env('FWD_ASUSER'));
    }

    public function getDockerComposeExec() : DockerComposeExec
    {
        return $this->wrapper;
    }

    public static function getDefaultArgs(): array
    {
        return ['-v'];
    }
}
