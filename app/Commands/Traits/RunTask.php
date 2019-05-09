<?php

namespace App\Commands\Traits;

trait RunTask
{
    public function runTask(string $title, \Closure $task)
    {
        $exitCode = null;

        $this->task($title, function() use (&$exitCode, $task) {
            return ! ($exitCode = $task());
        });

        return $exitCode;
    }
}
