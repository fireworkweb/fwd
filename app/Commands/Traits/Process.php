<?php

namespace App\Commands\Traits;

use Symfony\Component\Process\Process as SymfonyProcess;
use Symfony\Component\Process\Exception\ProcessFailedException;

trait Process
{
    public function process(
        $command,
        string $cwd = null,
        array $env = [],
        $timeout = 0,
        $callback = null
    ) {
        $callback = $callback ?: function ($type, $buffer) {
            $buffer = trim($buffer);

            switch ($type) {
                case 'err':
                    $this->warn($buffer);
                    break;

                case 'out':
                    $this->line($buffer);
                    break;
            }
        };

        (new SymfonyProcess($command, $cwd, $env, $timeout))->run($callback);
    }
}
