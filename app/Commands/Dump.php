<?php

namespace App\Commands;

use App\Environment;
use LaravelZero\Framework\Commands\Command;

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
    public function handle(Environment $environment): void
    {
        $this->table(['Variable', 'Value'], $this->getTableValues($environment));
    }

    /**
     * Get formattaed table values.
     *
     * @return array
     */
    protected function getTableValues(Environment $environment): array
    {
        return collect($environment->getValues())
            ->sortKeys()
            ->map(function ($value, $key) {
                return [ $key, $value ];
            })
            ->values()
            ->all();
    }
}
