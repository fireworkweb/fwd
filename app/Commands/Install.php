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

        if (! $this->option('force')) {
            if (File::exists($this->environment->getContextDockerCompose())) {
                $this->error('File "docker-compose.yml" already exists. (use -f to override)');

                return;
            }

            if (File::exists($this->environment->getContextEnv('.fwd'))) {
                $this->error('File ".fwd" already exists. (use -f to override)');

                return;
            }
        }

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

        $copied = File::copy(
            $this->environment->getDefaultDockerCompose($dockerComposeFileVersion),
            $this->environment->getContextDockerCompose()
        );

        if (false === $copied) {
            $this->error('Failed to write local "docker-compose.yml" file.');

            return 1;
        }

        $this->info('File "docker-compose.yml" copied.');
    }

    private function commentsOutAllVariables(string $env) : string
    {
        return preg_replace('/^([A-Z].*)$/m', '# $1', $env);
    }

    private function uncommentsLocalVariables(string $env) : string
    {
        $localVariables = [
            'FWD_IMAGE_APP',
            'FWD_IMAGE_CACHE',
            'FWD_IMAGE_NODE',
            'FWD_IMAGE_DATABASE',
        ];

        foreach ($localVariables as $variable) {
            $env = preg_replace("/^# ($variable=.*)$/m", '$1', $env);
        }

        return $env;
    }

    private function validateDockerComposeFileVersion(string $dockerComposeFileVersion) : void
    {
        if (! in_array($dockerComposeFileVersion, ['2', '3.7'])) {
            $this->error('Bad docker-compose-version option; valid values are either 2 or 3.7');

            throw new InvalidArgumentException('docker-compose-version must be either 2 or 3.7');
        }

        if ($dockerComposeFileVersion !== '3.7') {
            $this->warn('Deprecated: not using the latest docker-compose-version=3.7 is deprecated and soon will lose support.');
        }
    }
}
