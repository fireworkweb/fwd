<?php

namespace App\Builder;

class PhpQa extends Command
{
    public function makeWrapper() : ?Command
    {
        return (new DockerRun())->addArgument(env('FWD_IMAGE_PHP_QA'));
    }

    public function getDefaultArgs(): array
    {
        return ['php -v'];
    }
}
