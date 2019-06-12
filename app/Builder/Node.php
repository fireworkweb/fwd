<?php

namespace App\Builder;

class Node extends Command
{
    public function __construct(...$args)
    {
        $this->setWrapper(new DockerRun());

        parent::__construct(env('FWD_IMAGE_NODE'), ...array_merge([
            'node',
        ], $args));
    }
}
