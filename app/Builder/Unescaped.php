<?php

namespace App\Builder;

class Unescaped
{
    /** @var string $string */
    protected $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    public static function make(string $command) : Unescaped
    {
        return new static($command);
    }

    public function __toString() : string
    {
        return $this->string;
    }
}
