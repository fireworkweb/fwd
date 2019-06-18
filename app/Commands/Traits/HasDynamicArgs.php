<?php

namespace App\Commands\Traits;

trait HasDynamicArgs
{
    protected function specifyParameters()
    {
        // ignores the arguments/options signature
        $this->ignoreValidationErrors();
    }

    public function getArgs() : string
    {
        $args = (string) $this->input;

        return ($pos = mb_strpos($args, ' '))
            ? mb_substr($args, $pos + 1)
            : $this->getDefaultArgs();
    }

    /**
     * Get default args when empty.
     *
     * @return string
     */
    public function getDefaultArgs(): string
    {
        return '';
    }
}
