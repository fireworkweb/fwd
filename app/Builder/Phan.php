<?php

namespace App\Builder;

class Phan extends Builder
{
    public function getProgramName() : string
    {
        return 'phan';
    }

    public function makeWrapper() : ?Builder
    {
        return PhpQa::make();
    }

    public function getPhpQa(): PhpQa
    {
        return $this->wrapper;
    }

    public static function getDefaultArgs(): array
    {
        return ['--color -p -l app -iy 5'];
    }
}
