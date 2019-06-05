<?php

namespace App\Builder\Concerns;

use App\Builder\Argument;
use App\Builder\Unescaped;
use App\Builder\Command;

trait HasWrapper
{
    /** @var Command */
    protected $wrapper = [];

    public function setWrapper(Command $command)
    {
        $this->wrapper = $command;

        return $this;
    }

    public function __toString() : string
    {
        return $this->wrapper->addArgument(
            Unescaped::make(parent::__toString())
        )->__toString();
    }
}
