<?php

namespace App\Builder;

class Argument
{
    /** @var string $argn */
    protected $argn;

    /** @var string|null|Unescaped $value */
    protected $value;

    /** @var string $value */
    protected $separator;

    public function __construct($argn = '', $value = null, string $separator = '=')
    {
        $this->argn = $argn;
        $this->value = $value;
        $this->separator = $separator;
    }

    public function __toString() : string
    {
        if ( ! is_null($this->value)) {
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
        if (is_object($this->value) && $this->value instanceof Unescaped) {
            return $this->value->__toString();
        }

        return escapeshellarg($this->value);
    }
}
