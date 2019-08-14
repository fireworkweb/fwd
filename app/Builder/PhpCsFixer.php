<?php

namespace App\Builder;

class PhpCsFixer extends Command
{
    public function getProgramName() : string
    {
        return 'php-cs-fixer';
    }

    public function makeWrapper() : ?Command
    {
        return PhpQa::make();
    }

    public function getPhpQa(): PhpQa
    {
        return $this->wrapper;
    }

    public function getDefaultArgs(): array
    {
        return ['fix app --format=txt --dry-run --diff --verbose'];
    }
}
