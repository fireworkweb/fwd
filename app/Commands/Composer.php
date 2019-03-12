<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use App\Commands\Traits\Process;
use App\Commands\Traits\HasDynamicArgs;

class Composer extends Command
{
    use Process, HasDynamicArgs;

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
        $this->dockerCompose(
            'exec app composer',
            $this->args
        );
    }
}
