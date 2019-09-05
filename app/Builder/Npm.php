<?php

namespace App\Builder;

class Npm extends Builder
{
    public function getProgramName() : string
    {
        return 'npm';
    }

    public function makeWrapper() : ?Builder
    {
        return (new DockerRun())->addArgument(env('FWD_IMAGE_NODE'));
    }

    public static function getDefaultArgs(): array
    {
        return ['-v'];
    }
}
