<?php

namespace Tests\Unit;

use App\Builder\DockerComposeExec;
use App\Builder\Unescaped;
use Tests\TestCase;

class DockerComposeExecCommandTest extends TestCase
{
    public function testDockerComposeExec()
    {
        $comm = new DockerComposeExec();

        $this->assertEquals($this->makeDockerComposeExecString(), (string) $comm);
    }

    public function testDockerComposeExecWithUser()
    {
        $comm = new DockerComposeExec();

        $comm->setUser('foo');

        $this->assertEquals($this->makeDockerComposeExecUserString('foo'), (string) $comm);
    }

    public function testDockerComposeExecService()
    {
        $comm = new DockerComposeExec(Unescaped::make('foo'));

        $this->assertEquals($this->makeDockerComposeExecString('foo'), (string) $comm);
    }

    public function testDockerComposeExecServiceWithEnv()
    {
        $comm = new DockerComposeExec(Unescaped::make('foo'));

        $comm->addEnv('BAR', 'zum');

        $this->assertEquals($this->makeDockerComposeExecString('-e BAR=\'zum\' foo'), (string) $comm);
    }

    public function testDockerComposeExecServiceWithEnvEscaped()
    {
        $comm = new DockerComposeExec(Unescaped::make('foo'));

        $comm->addEnv('BAR', 'zum zap');

        $this->assertEquals($this->makeDockerComposeExecString('-e BAR=\'zum zap\' foo'), (string) $comm);
    }
}
