<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use App\Environment;

class Dump extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'dump';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Dump all environment variables parsed for fwd.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(): void
    {
        $this->table(['Variable', 'Value'], $this->getTableValues());
    }

    /**
     * Get formattaed table values.
     *
     * @return array
     */
    protected function getTableValues(): array
    {
        return collect(Environment::getValues())
            ->sortKeys()
            ->map(function ($value, $key) {
                return [ $key, $value ];
            })
            ->values()
            ->all();
    }
}
