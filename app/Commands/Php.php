<?php

namespace App\Commands;

use App\Commands\Traits\HasDynamicArgs;
use App\Commands\Traits\Process;
use LaravelZero\Framework\Commands\Command;

class Php extends Command
{
    use Process, HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'php';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run shell commands in the PHP-FPM container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->dockerCompose(
            'exec app',
            $this->args ?: 'php -v'
        );
    }
}
