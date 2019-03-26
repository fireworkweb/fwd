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

    public function testSshMysql()
    {
        $this->artisan('ssh mysql')->assertExitCode(0);

        $this->assertDockerComposeExec('mysql bash');
    }

    public function testSshMysqlSh()
    {
        $this->artisan('ssh mysql --shell=sh')->assertExitCode(0);

        $this->assertDockerComposeExec('mysql sh');
    }
}
