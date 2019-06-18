<?php

namespace App\Events;

use App\Builder\Command;

class BeforeExecuteCommand
{
    /** @var Command $command */
    protected $command;

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    public function getCommand()
    {
        return $this->command;
    }
}
