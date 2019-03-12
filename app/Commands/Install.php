<?php

namespace App\Commands;

use App\Commands\Traits\Process;
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
        if (! $this->option('force') && File::exists($this->getContextDockerCompose())) {
            $this->error('File docker-compose.yml already exists.');
            return;
        }

        $yml = file_get_contents($this->getDefaultDockerCompose());
        $yml = str_replace('${FWD_CONTEXT_PATH}', '.', $yml);
        file_put_contents($this->getContextDockerCompose(), $yml);
        $this->info('File docker-compose.yml copied.');

        File::copy(base_path() . '/.env.default', getcwd() . '/.fwd');
        $this->info('File .fwd copied.');
    }
}
