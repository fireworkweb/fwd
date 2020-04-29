<?php

namespace App\Builder;

class PhpSecurityChecker extends Builder
{
    public function getProgramName() : string
    {
        return 'security-checker';
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
        return ['security:check', 'composer.lock'];
    }
}
