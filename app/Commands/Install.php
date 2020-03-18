<?php

namespace App\Commands;

use Illuminate\Support\Facades\File;
use InvalidArgumentException;

class Install extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'install
                                {--laravel : Enable config optimizations to laravel (sync .env) }
                                {--f|force : Overwrites project files (docker-compose.yml and .fwd)}
                                {--docker-compose-version=3.7 : Which Docker Compose file version to use. Default is 3.7}';

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
        $dockerComposeFileVersion = $this->option('docker-compose-version');

        $this->validateDockerComposeFileVersion($dockerComposeFileVersion);

        if ($this->option('force') || ! File::exists($this->environment->getContextEnv('.fwd'))) {
            $localEnv = $this->uncommentsLocalVariables(
                $this->commentsOutAllVariables(
                    File::get($this->environment->getDefaultFwd())
                )
            );

            $put = File::put($this->environment->getContextEnv('.fwd'), $localEnv);

            if (false === $put) {
                $this->error('Failed to write local ".fwd" file.');

                return 1;
            }

            $this->info('File ".fwd" copied.');
        } else {
            $this->warn('File ".fwd" already exists, skipping. (to override run again with --force)');
        }

        if ($this->option('force') || ! File::exists($this->environment->getContextDockerCompose())) {
            $copied = File::copy(
                $this->environment->getDefaultDockerCompose($dockerComposeFileVersion),
                $this->environment->getContextDockerCompose()
            );

            if (false === $copied) {
                $this->error('Failed to write local "docker-compose.yml" file.');

                return 1;
            }

            $this->info('File "docker-compose.yml" copied.');
        } else {
            $this->warn('File "docker-compose.yml" already exists, skipping. (to override run again with --force)');
        }

        if ($this->option('laravel')) {
            $this->laravel();
        }
    }

    private function commentsOutAllVariables(string $env): string
    {
        return preg_replace('/^([A-Z].*)$/m', '# $1', $env);
    }

    private function uncommentsLocalVariables(string $env): string
    {
        $localVariables = [
            'FWD_IMAGE_APP',
            'FWD_IMAGE_HTTP',
            'FWD_IMAGE_CACHE',
            'FWD_IMAGE_CHROMEDRIVER',
            'FWD_IMAGE_NODE',
            'FWD_IMAGE_DATABASE',
        ];

        foreach ($localVariables as $variable) {
            $env = preg_replace("/^# ({$variable}=.*)$/m", '$1', $env);
        }

        return $env;
    }

    private function validateDockerComposeFileVersion(string $dockerComposeFileVersion): void
    {
        if (! in_array($dockerComposeFileVersion, ['2', '3.7'])) {
            $this->error('Bad docker-compose-version option; valid values are either 2 or 3.7');

            throw new InvalidArgumentException('docker-compose-version must be either 2 or 3.7');
        }

        if ($dockerComposeFileVersion !== '3.7') {
            $this->warn('Deprecated: not using the latest docker-compose-version=3.7 is deprecated and soon will lose support.');
        }
    }

    private function laravel()
    {
        if (! File::exists($this->environment->getContextEnv('.env'))) {
            File::copy(
                $this->environment->getContextEnv('.env.example'),
                $this->environment->getContextEnv('.env')
            );
        }

        $env = File::get($this->environment->getContextEnv('.env'));

        $replace = [
            'DB_HOST' => 'database',
            'DB_DATABASE' => env('DB_DATABASE'),
            'DB_USERNAME' => env('DB_USERNAME'),
            'DB_PASSWORD' => env('DB_PASSWORD'),
            'CACHE_DRIVER' => 'redis',
            'QUEUE_CONNECTION' => 'redis',
            'QUEUE_DRIVER' => 'redis', // compatibility to laravel < 5.7
            'SESSION_DRIVER' => 'redis',
            'REDIS_HOST' => 'cache',
        ];

        foreach ($replace as $variable => $value) {
            $env = preg_replace("/^({$variable})=(.*)$/m", "$1={$value}", $env);
        }

        $put = File::put($this->environment->getContextEnv('.env'), $env);

        if (false === $put) {
            $this->error('Failed to write local ".env" file.');

            return 1;
        }

        $this->info('File ".env" updated.');
    }
}
