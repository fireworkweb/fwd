<?php

namespace App\Builder;

class NodeQa extends Command
{
    public function makeWrapper() : ?Command
    {
        return (new DockerRun())->addArgument(env('FWD_IMAGE_NODE_QA'));
    }
}
