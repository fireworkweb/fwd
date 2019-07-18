<?php

namespace App\Commands;

use App\Builder\Artisan;
use App\Commands\Traits\HasDynamicArgs;

class Dusk extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'dusk';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run artisan dusk commands inside the Application container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->commandExecutor->run(
            Artisan::make('dusk', $this->getArgs())
        );
    }
}
