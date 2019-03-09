<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use App\Commands\Traits\Process;

class Ps extends Command
{
    use Process;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'ps';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'ps';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->process('docker-compose ps');
    }
}
