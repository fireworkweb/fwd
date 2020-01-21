<?php

namespace App\Builder;

class DockerCompose extends Builder
{
    public function getProgramName() : string
    {
        return (string) env('FWD_DOCKER_COMPOSE_BIN');
    }

    public function makeArgs(...$args) : array
    {
        return array_merge([
            '-p',
            env('FWD_NAME'),
        ], parent::makeArgs(...$args));
    }

    public static function getDefaultArgs(): array
    {
        return ['ps'];
    }
}
