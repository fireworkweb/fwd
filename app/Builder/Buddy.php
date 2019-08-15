<?php

namespace App\Builder;

class Buddy extends Builder
{
    public function getProgramName() : string
    {
        return 'buddy';
    }

    public function makeWrapper() : ?Builder
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
