<?php

namespace App\Builder;

class PhpCpd extends Builder
{
    public function getProgramName() : string
    {
        return 'phpcpd';
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
        return ['--fuzzy app/'];
    }
}
