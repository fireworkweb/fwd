<?php

namespace App\Builder;

class Argument
{
    /** @var string $argn */
    protected $argn;

    /** @var string|null|Unescaped $argv */
    protected $argv;

    /** @var string $separator */
    protected $separator;

    public static function raw(string $arg) : Argument
    {
        return new static(Unescaped::make($arg));
    }

    public function __construct($argn = '', $argv = null, string $separator = '=')
    {
        $this->argn = $argn;
        $this->argv = $argv;
        $this->separator = $separator;
    }

    public function __toString() : string
    {
        if ( ! is_null($this->argv)) {
            return vsprintf('%s%s%s', [
                $this->argn,
                $this->separator,
                $this->parseValue(),
            ]);
        }

        if (is_string($this->argn) && ! starts_with($this->argn, '-')) {
            return escapeshellarg($this->argn);
        }

        return $this->argn;
    }

    private function parseValue() : string
    {
        if (is_object($this->argv) && $this->argv instanceof Unescaped) {
            return (string) $this->argv;
        }

        return escapeshellarg($this->argv);
    }
}
