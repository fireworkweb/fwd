<?php

namespace App\Commands\Traits;

trait HasDynamicArgs
{
    /** @var string $args */
    private $args;

    protected function specifyParameters()
    {
        // ignores the arguments/options signature
        $this->ignoreValidationErrors();

        // Access global argv to fetch all incoming arguments
        // to be forwarded to the destiny command
        global $argv;

        if (count($argv) <= 2) {
            return;
        }

        $this->args = implode(' ', array_slice($argv, 2));
    }
}
