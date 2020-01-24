<?php

namespace App\Commands\Traits;

trait HasDynamicArgs
{
    public function getArgs() : string
    {
        $args = (string) $this->input;

        return ($pos = mb_strpos($args, ' '))
            ? mb_substr($args, $pos + 1)
            : '';
    }

    protected function specifyParameters()
    {
        // ignores the arguments/options signature
        $this->ignoreValidationErrors();
    }
}
