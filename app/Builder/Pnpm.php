<?php

namespace App\Builder;

class Pnpm extends Builder
{
    public function getProgramName() : string
    {
        return 'pnpm';
    }

    public function makeWrapper() : ?Builder
    {
        return (new DockerRun())->addArgument(env('FWD_IMAGE_NODE'));
    }

    public static function getDefaultArgs(): array
    {
        return ['-v'];
    }
}
