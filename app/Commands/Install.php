<?php

namespace App\Commands;

use Illuminate\Support\Facades\File;

class Install extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'install {--f|force : Overwrites docker-compose.yml.}';

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
    public function handle()
    {
        if (! $this->option('force')) {
            if (File::exists($this->environment->getContextDockerCompose())) {
                $this->error('File "docker-compose.yml" already exists.');

                return;
            }

            if (File::exists($this->environment->getContextEnv('.fwd'))) {
                $this->error('File ".fwd" already exists.');

                return;
            }
        }

        File::copy(
            $this->environment->getDefaultDockerCompose(),
            $this->environment->getContextDockerCompose()
        );

        File::copy(
            $this->environment->getDefaultFwd(),
            $this->environment->getContextEnv('.fwd')
        );

        $this->info('File "docker-compose.yml" copied.');
        $this->info('File ".fwd" copied.');
    }
}
