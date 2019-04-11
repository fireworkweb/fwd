<?php

namespace App\Commands;

use App\Process;
use App\Commands\Traits\HasDynamicArgs;
use LaravelZero\Framework\Commands\Command;

class Artisan extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'artisan';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run artisan commands inside the Application container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Process $process)
    {
        return $process->dockerComposeExec('app php artisan', $this->getArgs());
    }
}
