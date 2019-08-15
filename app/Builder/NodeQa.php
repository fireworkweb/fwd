<?php

namespace App\Builder;

class NodeQa extends Builder
{
    public function makeWrapper() : ?Builder
    {
        return (new DockerRun())->addArgument(env('FWD_IMAGE_NODE_QA'));
    }

    public function getDefaultArgs(): array
    {
        return ['node -v'];
    }
}
