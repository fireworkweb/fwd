<?php

namespace App\Builder;

class Argument
{
    /** @var string $argn */
    protected $argn;

    /** @var string|null|Unescaped|Escaped|Argument $argv */
    protected $argv;

    /** @var string $separator */
    protected $separator;

    public function __construct($argn = '', $value = null, string $separator = '=')
    {
        $this->argn = $argn;
        $this->argv = static::makeArgv($value);
        $this->separator = $separator;
    }

    public function __toString() : string
    {
        if (! is_null($this->argv)) {
            return vsprintf('%s%s%s', [
                (string) $this->argn,
                $this->separator,
                (string) $this->argv,
            ]);
        }

        return (string) $this->argn;
    }

    public static function makeArgv($value)
    {
        return is_string($value)
            ? new Escaped($value)
            : $value;
    }
}
