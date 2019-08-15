<?php

namespace App\Builder;

class Artisan extends Builder
{
    public function getProgramName() : string
    {
        return 'artisan';
    }

    public function makeWrapper() : ?Builder
    {
        return Php::make();
    }

    public function getPhp() : Php
    {
        return $this->wrapper;
    }
}
