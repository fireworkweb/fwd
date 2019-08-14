<?php

namespace App\Builder;

class Npm extends Command
{
    public function getProgramName() : string
    {
        return 'npm';
    }

    public function makeWrapper() : ?Command
    {
        return (new DockerRun())->addArgument(env('FWD_IMAGE_NODE'));
    }

    public function getDefaultArgs(): array
    {
        return ['-v'];
    }
}
