<?php

namespace Tests\Feature;

use Tests\TestCase;

class SshTest extends TestCase
{
    public function testSshApp()
    {
        $this->artisan('ssh app')->assertExitCode(0);

        $this->assertDockerComposeExec('app bash');
    }

    public function testSshAppSh()
    {
        $this->artisan('ssh app --shell=sh')->assertExitCode(0);

        $this->assertDockerComposeExec('app sh');
    }

    public function testSshHttp()
    {
        $this->artisan('ssh http')->assertExitCode(0);

        $this->assertDockerComposeExec('http bash');
    }

    public function testSshHttpSh()
    {
        $this->artisan('ssh http --shell=sh')->assertExitCode(0);

        $this->assertDockerComposeExec('http sh');
    }

    public function testSshDatabase()
    {
        $this->artisan('ssh database')->assertExitCode(0);

        $this->assertDockerComposeExec('database bash');
    }

    public function testSshDatabaseSh()
    {
        $this->artisan('ssh database --shell=sh')->assertExitCode(0);

        $this->assertDockerComposeExec('database sh');
    }
}
