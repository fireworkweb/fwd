<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use App\Commands\Traits\Process;

class Up extends Command
{
    use Process;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'up
                            {--d|detach : Detached mode: Run containers in the background, print new container names.}
    ';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Up fwd';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->process([
            'docker-compose', 'up',
            $this->option('detach') ? '-d' : null,
        ]);
    }
}
