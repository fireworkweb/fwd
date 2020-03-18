<?php

namespace Tests\Feature;

use Tests\TestCase;

class ResetTest extends TestCase
{
    public function testReset()
    {
        $this->artisan('reset')->assertExitCode(0);

        $this->assertReset();
    }

    public function testResetWithClear()
    {
        $this->artisan('reset --clear')->assertExitCode(0);

        $this->assertReset();

        $this->asFwdUser()->assertDockerComposeExec('app php artisan clear-compiled');
        $this->asFwdUser()->assertDockerComposeExec('app php artisan cache:clear');
        $this->asFwdUser()->assertDockerComposeExec('app php artisan config:clear');
        $this->asFwdUser()->assertDockerComposeExec('app php artisan route:clear');
        $this->asFwdUser()->assertDockerComposeExec('app php artisan view:clear');
    }

    public function testResetWithClearLogs()
    {
        $this->artisan('reset --clear-logs')->assertExitCode(0);

        $this->assertReset();

        $this->setAsUser(null);
        $this->assertDockerComposeExec(
            'app rm -f',
            '\'' . base_path('storage/logs/*.log') . '\''
        );
    }

    public function testResetWithNoSeed()
    {
        $this->artisan('reset --no-seed')->assertExitCode(0);

        $this->assertReset(true);
    }

    public function testResetWithDusk()
    {
        $this->artisan('reset tests/fixtures/.env.dusk.local')->assertExitCode(0);

        $this->asFwdUser()->assertDockerComposeExec('app composer install');
        $this->setAsUser(null);
        $this->assertDockerComposeExec("-e MYSQL_PWD='secret' database mysql -u root -e 'drop database if exists dusk'");
        $this->assertDockerComposeExec("-e MYSQL_PWD='secret' database mysql -u root -e 'create database dusk'");
        $this->assertDockerComposeExec("-e MYSQL_PWD='secret' database mysql -u root -e 'grant all on dusk.* to docker@\"%\"'");

        $this->asFwdUser()->assertDockerComposeExec(
            '-e DB_PASSWORD=\'secret\'',
            '-e DB_USERNAME=\'docker\'',
            '-e DB_DATABASE=\'dusk\'',
            'app php artisan migrate:fresh --seed'
        );

        $this->assertDockerRun('fireworkweb/node:alpine yarn install');
        $this->assertDockerRun('fireworkweb/node:alpine yarn dev');
    }

    protected function assertReset($noSeed = false)
    {
        $this->asFwdUser()->assertDockerComposeExec('app composer install');
        $this->assertDockerComposeExec('cache redis-cli flushall');
        $this->setAsUser(null);
        $this->assertDockerComposeExec("-e MYSQL_PWD='secret' database mysql -u root -e 'drop database if exists docker'");
        $this->assertDockerComposeExec("-e MYSQL_PWD='secret' database mysql -u root -e 'create database docker'");
        $this->assertDockerComposeExec("-e MYSQL_PWD='secret' database mysql -u root -e 'grant all on docker.* to docker@\"%\"'");

        $this->asFwdUser()->assertDockerComposeExec(
            '-e DB_PASSWORD=\'secret\'',
            '-e DB_USERNAME=\'docker\'',
            '-e DB_DATABASE=\'docker\'',
            'app php artisan migrate:fresh ' . (! $noSeed ? '--seed' : '')
        );

        $this->assertDockerRun('fireworkweb/node:alpine yarn install');
        $this->assertDockerRun('fireworkweb/node:alpine yarn dev');
    }
}
