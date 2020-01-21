<?php

namespace App\Tasks;

use App\Builder\PhpMnd as PhpMndBuilder;

class PhpMnd extends Task
{
    public function run(...$args): int
    {
        return $this->runCommand(PhpMndBuilder::makeWithDefaultArgs(...$args));
    }
}
