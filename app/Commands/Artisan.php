<?php

namespace App\Commands;

use App\Commands\Traits\HasDynamicArgs;
use App\Commands\Traits\Process;
use LaravelZero\Framework\Commands\Command;

class Artisan extends Command
{
    use HasDynamicArgs, Process;

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
    public function handle()
    {
        $this->dockerCompose('exec app php artisan', $this->getArgs());
    }
}
