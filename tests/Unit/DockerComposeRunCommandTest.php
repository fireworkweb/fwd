<?php

namespace Tests\Unit;

use App\Environment;
use App\Builder\DockerComposeRun;
use App\Builder\Unescaped;
use Tests\TestCase;

class DockerComposeRunCommandTest extends TestCase
{
    public function testDockerComposeRun()
    {
        $comm = new DockerComposeRun();

        $this->assertEquals($this->makeDockerComposeRunString(), (string) $comm);
    }

    public function testDockerComposeRunWithCustomEnvVar()
    {
        app(Environment::class)->overloadEnv('tests/fixtures/.env.docker-compose_run');

        $comm = new DockerComposeRun();

        $this->assertEquals($this->makeDockerComposeRunString(), (string) $comm);
    }

    public function testDockerComposeRunWithUser()
    {
        $comm = new DockerComposeRun();

        $comm->setUser('foo');

        $this->assertEquals($this->makeDockerComposeRunUserString('foo'), (string) $comm);
    }

    public function testDockerComposeRunService()
    {
        $comm = new DockerComposeRun(Unescaped::make('foo'));

        $this->assertEquals($this->makeDockerComposeRunString('foo'), (string) $comm);
    }

    public function testDockerComposeRunServiceWithEnv()
    {
        $comm = new DockerComposeRun(Unescaped::make('foo'));

        $comm->addEnv('BAR', 'zum');

        $this->assertEquals($this->makeDockerComposeRunString('-e BAR=\'zum\' foo'), (string) $comm);
    }

    public function testDockerComposeRunServiceWithEnvEscaped()
    {
        $comm = new DockerComposeRun(Unescaped::make('foo'));

        $comm->addEnv('BAR', 'zum zap');

        $this->assertEquals($this->makeDockerComposeRunString('-e BAR=\'zum zap\' foo'), (string) $comm);
    }
}
