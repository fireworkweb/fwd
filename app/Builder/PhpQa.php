<?php

namespace App\Builder;

class PhpQa extends Command
{
    public function __construct(...$args)
    {
        $this->setWrapper(new DockerRun());

        parent::__construct(env('FWD_IMAGE_PHP_QA'), ...$args);
    }
}
