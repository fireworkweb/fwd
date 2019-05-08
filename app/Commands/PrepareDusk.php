<?php

namespace App\Commands;

use App\Process;
use App\Environment;
use App\Commands\Traits\ArtisanCall;
use LaravelZero\Framework\Commands\Command;

class PrepareDusk extends Command
{
    use ArtisanCall;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'prepare-dusk {envFile=.env.dusk.local}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a test dedicated database named dusk.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Environment $environment, Process $process)
    {
        $this->comment('Deprecated: use "fwd reset .env.dusk.local".');

        return $this->artisanCall('reset', [
            $this->argument('envFile'),
        ]);
    }
}
