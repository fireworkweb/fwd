<?php

namespace Tests\Unit;

use App\Builder\Argument;
use App\Builder\DockerCompose;
use App\Builder\Unescaped;
use Tests\TestCase;

class DockerComposeCommandTest extends TestCase
{
    public function testDockerCompose()
    {
        $comm = new DockerCompose();

        $this->assertEquals($this->dockerComposeString(), (string) $comm);
    }

    public function testDockerComposeInnerCommand()
    {
        $comm = new DockerCompose(Unescaped::make('exec'));

        $comm->addArgument(new Argument('-e', 'FOO=bar', ' '));
        $comm->addArgument('database mysql');
        $comm->addArgument(new Argument('-e', 'SELECT 1', ' '));

        $this->assertEquals(
            $this->dockerComposeExecString() . ' -e \'FOO=bar\' database mysql -e \'SELECT 1\'',
            (string) $comm
        );
    }
}
