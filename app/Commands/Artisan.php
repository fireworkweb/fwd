<?php

namespace App\Commands;

use App\CommandExecutor;
use App\Builder\Unescaped;
use App\Commands\Traits\HasDynamicArgs;
use App\Builder\Artisan as ArtisanCommand;
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
    public function handle(CommandExecutor $executor)
    {
        return $executor->run(ArtisanCommand::make($this->getArgs()));
    }
}
