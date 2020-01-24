<?php

namespace App\Builder;

class Escaped
{
    /** @var string $string */
    protected $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    public function __toString() : string
    {
        return escapeshellarg($this->string);
    }

    public static function make(string $command) : self
    {
        return new static($command);
    }
}
