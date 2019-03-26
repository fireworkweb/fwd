<?php

namespace App\Commands;

use App\Environment;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;

class Install extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'install
                            {--f|force : Overwrites docker-compose.yml.}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Install fwd configuration files locally in your project.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Environment $environment)
    {
        if (! $this->option('force')) {
            if (File::exists($environment->getContextDockerCompose())) {
                $this->error('File "docker-compose.yml" already exists.');

                return;
            }

            if (File::exists($environment->getContextEnv('.fwd'))) {
                $this->error('File ".fwd" already exists.');

                return;
            }
        }

        File::copy($environment->getDefaultDockerCompose(), $environment->getContextDockerCompose());
        $this->info('File "docker-compose.yml" copied.');

        File::copy($environment->getDefaultFwd(), $environment->getContextEnv('.fwd'));
        $this->info('File ".fwd" copied.');
    }
}
