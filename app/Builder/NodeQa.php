<?php

namespace App\Builder;

class NodeQa extends Command
{
    public function __construct(...$args)
    {
        $this->setWrapper(new DockerRun());

        parent::__construct(env('FWD_IMAGE_NODE_QA'), ...$args);
    }
}
