<?php

namespace Tests\Feature;

use Tests\TestCase;

class ResetDbTest extends TestCase
{
    public function testReset()
    {
        $this->artisan('reset-db')->assertExitCode(0);

        $this->setAsUser(null);
        $this->assertDockerComposeExec("-e MYSQL_PWD='secret' database mysql -u root -e 'drop database if exists docker'");
        $this->assertDockerComposeExec("-e MYSQL_PWD='secret' database mysql -u root -e 'create database docker'");
        $this->assertDockerComposeExec("-e MYSQL_PWD='secret' database mysql -u root -e 'grant all on docker.* to docker@\"%\"'");
    }

    public function testResetWithDusk()
    {
        $this->artisan('reset-db tests/fixtures/.env.dusk.local')->assertExitCode(0);

        $this->setAsUser(null);
        $this->assertDockerComposeExec("-e MYSQL_PWD='secret' database mysql -u root -e 'drop database if exists dusk'");
        $this->assertDockerComposeExec("-e MYSQL_PWD='secret' database mysql -u root -e 'create database dusk'");
        $this->assertDockerComposeExec("-e MYSQL_PWD='secret' database mysql -u root -e 'grant all on dusk.* to docker@\"%\"'");
    }
}
