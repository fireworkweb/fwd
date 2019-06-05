<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Builder\Unescaped;
use App\Builder\DockerComposeExec;

class DockerComposeExecCommandTest extends TestCase
{
    public function testDockerComposeExec()
    {
        $comm = new DockerComposeExec();

        $this->assertEquals($comm->toString(), 'docker-compose -p fwd exec -T');
    }

    public function testDockerComposeExecWithUser()
    {
        $comm = new DockerComposeExec();

        $comm->setUser('foo');

        $this->assertEquals($comm->toString(), 'docker-compose -p fwd exec -T --user foo');
    }

    public function testDockerComposeExecService()
    {
        $comm = new DockerComposeExec(Unescaped::make('foo'));

        $this->assertEquals($comm->toString(), 'docker-compose -p fwd exec -T foo');
    }

    public function testDockerComposeExecServiceWithEnv()
    {
        $comm = new DockerComposeExec(Unescaped::make('foo'));

        $comm->addEnv('BAR', 'zum');

        $this->assertEquals('docker-compose -p fwd exec -T -e BAR=\'zum\' foo', $comm->toString());
    }

    public function testDockerComposeExecServiceWithEnvEscaped()
    {
        $comm = new DockerComposeExec(Unescaped::make('foo'));

        $comm->addEnv('BAR', 'zum zap');

        $this->assertEquals('docker-compose -p fwd exec -T -e BAR=\'zum zap\' foo', $comm->toString());
    }
}
