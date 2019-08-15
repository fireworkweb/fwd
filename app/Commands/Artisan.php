<?php

namespace App\Commands;

use App\Commands\Traits\HasDynamicArgs;
use App\Builder\Artisan as ArtisanBuilder;

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
    public function handle()
    {
        return $this->commandExecutor->run(
            ArtisanBuilder::make($this->getArgs())
        );
    }
}
