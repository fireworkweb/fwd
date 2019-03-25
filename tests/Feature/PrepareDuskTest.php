<?php


namespace Tests\Feature;


use Tests\TestCase;
use App\Environment;
use App\Process;


class PrepareDuskTest extends TestCase
{
    public function testDusk()
    {
        $this->mock(Environment::class, function ($mock) {
            $mock->shouldReceive('getContextEnv')
                ->once()
                ->with('.env.dusk.local')
                ->andReturn(base_path('tests/fixtures/.env.dusk.local'));
        })->makePartial();

        $this->artisan('prepare-dusk')->assertExitCode(0);

        $this->assertCommandCalled('mysql-raw', ['-e', 'drop database if exists dusk']);
        $this->assertCommandCalled('mysql-raw', ['-e', 'create database dusk']);
        $this->assertCommandCalled('mysql-raw', ['-e', 'grant all on dusk.* to docker@"%"']);

        $this->assertDockerComposeExec(
            '-e DB_DATABASE=dusk',
            '-e DB_USERNAME=docker',
            '-e DB_PASSWORD=secret',
            'app php artisan migrate:fresh --seed',
        );
    }
}
