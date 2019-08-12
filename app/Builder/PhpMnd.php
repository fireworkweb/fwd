<?php

namespace App\Builder;

class PhpMnd extends Command
{
    public function getProgramName()
    {
        return 'phpmnd';
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
            'app/',
            '--ignore-funcs=round,sleep,abort,strpad,number_format',
            '--exclude=tests',
            '--progress',
            '--extensions=default_parameter,-return,argument',
        ];
    }
}
