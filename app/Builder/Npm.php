<?php

namespace App\Builder;

class Npm extends Command
{
    public function getProgramName()
    {
        return 'npm';
    }

    public function makeWrapper() : ?Command
    {
        return (new DockerRun())->addArgument(env('FWD_IMAGE_NODE'));
    }
}
