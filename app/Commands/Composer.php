<?php

namespace App\Commands;

use App\Commands\Traits\Process;
use App\Commands\Traits\HasDynamicArgs;
use LaravelZero\Framework\Commands\Command;

class Composer extends Command
{
    use HasDynamicArgs, Process;

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
    public function handle()
    {
        $this->dockerCompose('exec app composer', $this->getArgs());
    }
}
