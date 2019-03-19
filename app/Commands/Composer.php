<?php

namespace App\Commands;

use App\Commands\Traits\HasDynamicArgs;
use App\Process;
use LaravelZero\Framework\Commands\Command;

class Composer extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'composer';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run composer within the Application container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Process $process)
    {
        $process->dockerCompose('exec app composer', $this->getArgs());
    }
}
