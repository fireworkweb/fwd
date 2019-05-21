<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Builder\DockerCompose;
use App\Builder\Unescaped;

class DockerComposeCommandTest extends TestCase
{
    public function testDocker()
    {
        $comm = new DockerCompose();

        $this->assertEquals($comm->toString(), 'docker-compose -p fwd');
    }

    public function testDockerComposeInnerCommand()
    {
        $comm = new DockerCompose(Unescaped::make('exec'));

        $comm->addArgument('-e');
        $comm->addArgument('FOO=bar');
        $comm->addArgument(Unescaped::make('mysql mysql'));
        $comm->addArgument('-e');
        $comm->addArgument('SELECT 1');

        $this->assertEquals($comm->toString(), 'docker-compose -p fwd exec -e \'FOO=bar\' mysql mysql -e \'SELECT 1\'');
    }
}
