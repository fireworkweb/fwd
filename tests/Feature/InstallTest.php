<?php

namespace Tests\Feature;

use App\Environment;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class InstallTest extends TestCase
{
    public function testInstall()
    {
        File::shouldReceive('exists')
            ->andReturn(false);

        File::shouldReceive('copy')
            ->andReturn(true);

        File::shouldReceive('get')
            ->andReturn("var1\nvar2");

        File::shouldReceive('put')
            ->andReturn(100);

        $this->artisan('install')
            ->expectsOutput('File ".fwd" copied.')
            ->expectsOutput('File "docker-compose.yml" copied.');

        $this->assertCommandCalled('install');
    }

    public function testInstallAgain()
    {
        File::shouldReceive('exists')
            ->andReturn(true);

        $this->artisan('install')
            ->expectsOutput('File "docker-compose.yml" already exists. (use -f to override)');

        $this->assertCommandCalled('install');
    }

    public function testDockerComposeExists()
    {
        $environment = app(Environment::class);

        File::shouldReceive('exists')
            ->with($environment->getContextDockerCompose())
            ->andReturn(true);

        $this->artisan('install')
            ->expectsOutput('File "docker-compose.yml" already exists. (use -f to override)');

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
            ->expectsOutput('File ".fwd" already exists. (use -f to override)');

        $this->assertCommandCalled('install');
    }

    public function testForceReinstall()
    {
        File::shouldReceive('exists')
            ->andReturn(true);

        File::shouldReceive('copy')
            ->andReturn(true);

        File::shouldReceive('get')
            ->andReturn("var1\nvar2");

        File::shouldReceive('put')
            ->andReturn(100);

        $this->artisan('install --force')
            ->expectsOutput('File ".fwd" copied.')
            ->expectsOutput('File "docker-compose.yml" copied.');

        $this->assertCommandCalled('install --force');
    }

    public function testVariablesAreCommentedOut()
    {
        File::shouldReceive('exists')
            ->andReturn(true);

        File::shouldReceive('copy')
            ->andReturn(true);

        File::shouldReceive('get')
            ->andReturn("FWD_VAR=x");

        File::shouldReceive('put')
            ->withArgs(function (string $file, string $env) {
                $this->assertEquals($env, '# FWD_VAR=x');
                $this->assertStringEndsWith('.fwd', $file);

                return true;
            });

        $this->artisan('install --force')
            ->expectsOutput('File ".fwd" copied.')
            ->expectsOutput('File "docker-compose.yml" copied.');
    }

    public function testVariablesAreNotCommentedOut()
    {
        File::shouldReceive('exists')
            ->andReturn(true);

        File::shouldReceive('copy')
            ->andReturn(true);

        File::shouldReceive('get')
            ->andReturn("FWD_IMAGE_APP=x");

        File::shouldReceive('put')
            ->withArgs(function (string $file, string $env) {
                $this->assertEquals($env, 'FWD_IMAGE_APP=x');
                $this->assertStringEndsWith('.fwd', $file);

                return true;
            });

        $this->artisan('install --force')
            ->expectsOutput('File ".fwd" copied.')
            ->expectsOutput('File "docker-compose.yml" copied.');
    }
}
