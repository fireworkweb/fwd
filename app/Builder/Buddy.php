<?php

namespace App\Builder;

class Buddy extends Command
{
    public function getProgramName() : string
    {
        return 'buddy';
    }

    public function makeWrapper() : ?Command
    {
        return NodeQa::make();
    }

    public function getNodeQa() : Node
    {
        return $this->wrapper;
    }

    public function getDefaultArgs(): array
    {
        return ['src/'];
    }
}
