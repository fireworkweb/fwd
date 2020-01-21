<?php

namespace App\Events;

use App\Builder\Builder;

class BeforeExecuteCommand
{
    /** @var Builder $builder */
    protected $builder;

    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    public function getCommand()
    {
        return $this->builder;
    }
}
