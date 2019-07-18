<?php

namespace App\Builder;

class Artisan extends Command
{
    public function getProgramName()
    {
        return 'artisan';
    }

    public function makeWrapper() : ?Command
    {
        return Php::make();
    }

    public function getPhp() : Php
    {
        return $this->wrapper;
    }
}
