<?php

namespace App\Builder;

class Yarn extends Builder
{
    public function getProgramName() : string
    {
        return 'yarn';
    }

    public function makeWrapper() : ?Builder
    {
        return (new DockerRun())->addArgument(env('FWD_IMAGE_NODE'));
    }
}
