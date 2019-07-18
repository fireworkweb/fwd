<?php

namespace App\Commands;

class PrepareDusk extends Reset
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'prepare-dusk {envFile=.env.dusk.local} {--clear} {--clear-logs} {--no-seed}';

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
    public function handle()
    {
        $this->comment('Deprecated: use "fwd reset .env.dusk.local".');

        return parent::handle();
    }
}
