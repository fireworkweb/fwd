<?php

namespace App\Builder;

class PhpMd extends Command
{
    public function getProgramName() : string
    {
        return 'phpmd';
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
        return [
            'app/ text',
            implode(',', [
                'phpmd/codesize.xml',
                'phpmd/controversial.xml',
                'phpmd/design.xml',
                'phpmd/naming.xml',
                'unusedcode',
                'phpmd/cleancode.xml',
            ]),
        ];
    }
}
