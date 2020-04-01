<?php

namespace Tests\Feature;

use App\Environment;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class InstallTest extends TestCase
{
    public function testInstall()
    {
        $this->mockFwd();
        $this->mockDockerCompose();

        $this->artisan('install')
            ->expectsOutput('File ".fwd" copied.')
            ->expectsOutput('File "docker-compose.yml" copied.');

        $this->assertCommandCalled('install');
    }

    public function testInstallAgain()
    {
        $this->mockFwd(true);
        $this->mockDockerCompose(true);

        $this->artisan('install')
            ->expectsOutput('File ".fwd" already exists, skipping. (to override run again with --force)')
            ->expectsOutput('File "docker-compose.yml" already exists, skipping. (to override run again with --force)');

        $this->assertCommandCalled('install');
    }

    public function testDockerComposeExists()
    {
        $this->mockFwd();
        $this->mockDockerCompose(true);

        $this->artisan('install')
            ->expectsOutput('File ".fwd" copied.')
            ->expectsOutput('File "docker-compose.yml" already exists, skipping. (to override run again with --force)');

        $this->assertCommandCalled('install');
    }

    public function testFwdExists()
    {
        $this->mockFwd(true);
        $this->mockDockerCompose();

        $this->artisan('install')
            ->expectsOutput('File ".fwd" already exists, skipping. (to override run again with --force)')
            ->expectsOutput('File "docker-compose.yml" copied.');

        $this->assertCommandCalled('install');
    }

    public function testPresetLaravel()
    {
        $this->mockFwd();
        $this->mockDockerCompose();
        $this->mockLaravel();

        $this->artisan('install --preset=laravel')
            ->expectsOutput('File ".fwd" copied.')
            ->expectsOutput('File "docker-compose.yml" copied.')
            ->expectsOutput('File ".env" updated.');

        $this->assertCommandCalled('install --preset=laravel');
    }

    public function testForce()
    {
        $this->mockFwd(true);
        $this->mockDockerCompose(true);

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
            ->andReturn('FWD_VAR=x');

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
            ->andReturn('FWD_IMAGE_APP=x');

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

    protected function mockFwd($exists = false)
    {
        $environment = app(Environment::class);

        File::shouldReceive('exists')
            ->with($environment->getContextEnv('.fwd'))
            ->andReturn($exists);

        File::shouldReceive('get')
            ->with($environment->getDefaultFwd())
            ->andReturn("var1\nvar2");

        File::shouldReceive('put')
            ->with($environment->getContextEnv('.fwd'), "var1\nvar2")
            ->andReturn(100);
    }

    protected function mockDockerCompose($exists = false)
    {
        $environment = app(Environment::class);

        File::shouldReceive('exists')
            ->with($environment->getContextDockerCompose())
            ->andReturn($exists);

        File::shouldReceive('copy')
            ->with($environment->getDefaultDockerCompose('3.7'), $environment->getContextDockerCompose())
            ->andReturn(true);
    }

    protected function mockLaravel()
    {
        $environment = app(Environment::class);

        File::shouldReceive('exists')
            ->with($environment->getContextEnv('.env'))
            ->andReturn(false);

        File::shouldReceive('copy')
            ->with($environment->getContextEnv('.env.example'), $environment->getContextEnv('.env'))
            ->andReturn(true);

        File::shouldReceive('get')
            ->with($environment->getContextEnv('.env'))
            ->andReturn("var1\nvar2");

        File::shouldReceive('put')
            ->with($environment->getContextEnv('.env'), "var1\nvar2")
            ->andReturn(true);
    }
}
