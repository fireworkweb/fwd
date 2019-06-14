<?php

namespace App\Builder;

class Node extends Command
{
    public function getProgramName()
    {
        return 'node';
    }

    public function makeWrapper() : ?Command
    {
        return (new DockerRun())->addArgument(env('FWD_IMAGE_NODE'));
    }
}
