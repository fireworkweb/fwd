<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Environment;
use Illuminate\Support\Facades\File;

class InstallTest extends TestCase
{
    public function testInstall()
    {
        File::shouldReceive('exists')
            ->andReturn(false);

        File::shouldReceive('copy')
            ->andReturn(true);

        $this->artisan('install')
            ->expectsOutput('File "docker-compose.yml" copied.')
            ->expectsOutput('File ".fwd" copied.');

        $this->assertCommandCalled('install');
    }

    public function testInstallAgain()
    {
        File::shouldReceive('exists')
            ->andReturn(true);

        $this->artisan('install')
            ->expectsOutput('File "docker-compose.yml" already exists.');

        $this->assertCommandCalled('install');
    }

    public function testDockerComposeExists()
    {
        $environment = app(Environment::class);

        File::shouldReceive('exists')
            ->with($environment->getContextDockerCompose())
            ->andReturn(true);

        $this->artisan('install')
            ->expectsOutput('File "docker-compose.yml" already exists.');

        $this->assertCommandCalled('install');
    }

    public function testFwdExists()
    {
        $environment = app(Environment::class);

        File::shouldReceive('exists')
            ->with($environment->getContextDockerCompose())
            ->andReturn(false);

        File::shouldReceive('exists')
            ->with($environment->getContextEnv('.fwd'))
            ->andReturn(true);

        $this->artisan('install')
            ->expectsOutput('File ".fwd" already exists.');

        $this->assertCommandCalled('install');
    }

    public function testForceReinstall()
    {
        File::shouldReceive('exists')
            ->andReturn(true);

        File::shouldReceive('copy')
            ->andReturn(true);

        $this->artisan('install --force')
            ->expectsOutput('File "docker-compose.yml" copied.')
            ->expectsOutput('File ".fwd" copied.');

        $this->assertCommandCalled('install --force');
    }
}
