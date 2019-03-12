<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use App\Commands\Traits\Process;

class Start extends Command
{
    use Process;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'start';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Start fwd environment containers.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->dockerCompose('up', '-d');
    }
}
