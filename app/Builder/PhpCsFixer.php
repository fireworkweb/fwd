<?php

namespace App\Builder;

class PhpCsFixer extends Builder
{
    public function getProgramName() : string
    {
        return 'php-cs-fixer';
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
        return ['fix app --format=txt --dry-run --diff --verbose'];
    }
}
