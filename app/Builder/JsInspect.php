<?php

namespace App\Builder;

class JsInspect extends Command
{
    public function getProgramName() : string
    {
        return 'jsinspect';
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
