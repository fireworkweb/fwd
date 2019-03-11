<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use Illuminate\Support\Facades\File;
use App\Commands\Traits\Process;

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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (! $this->option('force') && File::exists($this->getContextDockerCompose())) {
            $this->error('File docker-compose.yml already exists.');
            return;
        }

        File::copy($this->getDefaultDockerCompose(), $this->getContextDockerCompose());

        $this->info('File docker-compose.yml copied.');
    }
}
