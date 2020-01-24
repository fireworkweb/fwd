<?php

namespace App\Builder;

class PhpQa extends Builder
{
    public function getProgramName() : string
    {
        return '';
    }

    public function makeWrapper() : ?Builder
    {
        return (new DockerRun())->addArgument(env('FWD_IMAGE_PHP_QA'));
    }

    public static function getDefaultArgs(): array
    {
        return ['php -v'];
    }
}
