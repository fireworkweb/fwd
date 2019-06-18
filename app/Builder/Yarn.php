<?php

namespace App\Builder;

class Yarn extends Command
{
    public function getProgramName()
    {
        return 'yarn';
    }

    public function makeWrapper() : ?Command
    {
        return (new DockerRun())->addArgument(env('FWD_IMAGE_NODE'));
    }
}
