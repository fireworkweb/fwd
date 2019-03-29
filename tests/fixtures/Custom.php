<?php

use App\Process;
use App\Commands\Traits\ArtisanCall;
use App\Commands\Traits\HasDynamicArgs;
use LaravelZero\Framework\Commands\Command;

class Custom extends Command
{
    use ArtisanCall, HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'custom';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'custom command';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Process $process)
    {
        $process->process(['echo', 'custom']);
    }
}
