<?php

namespace App\Builder;

class Docker extends Builder
{
    public function getProgramName() : string
    {
        return (string) env('FWD_DOCKER_BIN');
    }

    public static function getDefaultArgs(): array
    {
        return ['ps'];
    }
}
