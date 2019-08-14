<?php

namespace App\Builder;

class PhpCpd extends Command
{
    public function getProgramName() : string
    {
        return 'phpcpd';
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
        return ['--fuzzy app/'];
    }
}
