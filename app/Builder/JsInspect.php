<?php

namespace App\Builder;

class JsInspect extends Builder
{
    public function getProgramName() : string
    {
        return 'jsinspect';
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
