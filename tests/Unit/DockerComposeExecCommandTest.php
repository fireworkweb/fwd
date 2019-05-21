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

        $this->assertEquals($comm->toString(), 'docker-compose -p fwd exec');
    }

    public function testDockerComposeExecWithUser()
    {
        $comm = new DockerComposeExec();

        $comm->setUser('foo');

        $this->assertEquals($comm->toString(), 'docker-compose -p fwd exec --user foo');
    }

    public function testDockerComposeExecService()
    {
        $comm = new DockerComposeExec(Unescaped::make('foo'));

        $this->assertEquals($comm->toString(), 'docker-compose -p fwd exec foo');
    }

    public function testDockerComposeExecServiceWithEnv()
    {
        $comm = new DockerComposeExec(Unescaped::make('foo'));

        $comm->addEnv('BAR', 'zum');

        $this->assertEquals($comm->toString(), 'docker-compose -p fwd exec -e \'BAR=\'\\\'\'zum\'\\\'\'\' foo');
    }

    public function testDockerComposeExecServiceWithEnvEscaped()
    {
        $comm = new DockerComposeExec(Unescaped::make('foo'));

        $comm->addEnv('BAR', 'zum zap');

        $this->assertEquals($comm->toString(), 'docker-compose -p fwd exec -e \'BAR=\'\\\'\'zum zap\'\\\'\'\' foo');
    }
}
