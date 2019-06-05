<?php

namespace App\Builder;

use App\Builder\Concerns\HasWrapper;

class Npm extends Command
{
    use HasWrapper;

    public function __construct(...$args)
    {
        $this->setWrapper(new DockerRun());

        parent::__construct(env('FWD_IMAGE_NODE'), ...array_merge([
            Argument::raw('npm'),
        ], $args));
    }
}
