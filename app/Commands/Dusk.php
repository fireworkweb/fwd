<?php

namespace App\Commands;

use App\Process;
use App\Commands\Traits\ArtisanCall;
use App\Commands\Traits\HasDynamicArgs;
use LaravelZero\Framework\Commands\Command;

class Dusk extends Command
{
    use ArtisanCall, HasDynamicArgs;

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
    public function handle(Process $process)
    {
        $this->artisanCall('prepare-dusk');

        return $process->asFWDUser()->dockerComposeExec('app php artisan dusk', $this->getArgs());
    }
}
