<?php

namespace App\Commands;

use App\Commands\Traits\Process;
use App\Environment;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;

class Install extends Command
{
    use Process;

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
    public function handle()
    {
        if (! $this->option('force')) {
            if (File::exists(Environment::getContextDockerCompose())) {
                $this->error('File "docker-compose.yml" already exists.');
                return;
            }

            if (File::exists(Environment::getContextFwd())) {
                $this->error('File ".fwd" already exists.');
                return;
            }
        }

        File::copy(Environment::getDefaultDockerCompose(), Environment::getContextDockerCompose());
        $this->info('File "docker-compose.yml" copied.');

        File::copy(Environment::getDefaultFwd(), Environment::getContextFwd());
        $this->info('File ".fwd" copied.');
    }
}
