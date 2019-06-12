<?php

namespace App\Builder;

class Yarn extends Command
{
    public function __construct(...$args)
    {
        $this->setWrapper(new DockerRun());

        parent::__construct(env('FWD_IMAGE_NODE'), ...array_merge([
            'yarn',
        ], $args));
    }
}
