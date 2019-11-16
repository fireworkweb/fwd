<?php

namespace App\Builder;

class Node extends Builder
{
    public function getProgramName() : string
    {
        return 'node';
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
