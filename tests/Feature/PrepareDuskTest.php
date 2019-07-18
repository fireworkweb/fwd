<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Environment;

class PrepareDuskTest extends TestCase
{
    public function testResetWithDusk()
    {
        $this->mock(Environment::class, function ($mock) {
            $mock->shouldReceive('getContextEnv')
                ->once()
                ->with('.env.dusk.local')
                ->andReturn(base_path('tests/fixtures/.env.dusk.local'));
        })->makePartial();

        $this->artisan('prepare-dusk')->assertExitCode(0);

        $this->asFwdUser()->assertDockerComposeExec('app composer install');
        $this->setAsUser(null);
        $this->assertDockerComposeExec("-e MYSQL_PWD='secret' mysql mysql -u root -e 'drop database if exists dusk'");
        $this->assertDockerComposeExec("-e MYSQL_PWD='secret' mysql mysql -u root -e 'create database dusk'");
        $this->assertDockerComposeExec("-e MYSQL_PWD='secret' mysql mysql -u root -e 'grant all on dusk.* to docker@\"%\"'");

        $this->asFwdUser()->assertDockerComposeExec(
            '-e DB_PASSWORD=\'secret\'',
            '-e DB_USERNAME=\'docker\'',
            '-e DB_DATABASE=\'dusk\'',
            'app php artisan migrate:fresh --seed'
        );

        $this->assertDockerRun('fireworkweb/node:alpine yarn install');
        $this->assertDockerRun('fireworkweb/node:alpine yarn dev');
    }
}
