<?php

namespace App\Builder;

class PhpMd extends Builder
{
    public function getProgramName() : string
    {
        return 'phpmd';
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
