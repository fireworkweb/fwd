<?php

namespace App\Builder;

class PhpMnd extends Builder
{
    public function getProgramName() : string
    {
        return 'phpmnd';
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
            'app/',
            '--ignore-funcs=round,sleep,abort,strpad,number_format',
            '--exclude=tests',
            '--progress',
            '--extensions=default_parameter,-return,argument',
        ];
    }
}
