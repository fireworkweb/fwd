<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Builder\Docker;
use App\Builder\Unescaped;

class DockerCommandTest extends TestCase
{
    public function testDocker()
    {
        $comm = new Docker();

        $this->assertEquals($comm->toString(), 'docker');
    }

    public function testDockerInnerCommand()
    {
        $comm = new Docker(Unescaped::make('run'), '-e', 'FOO=bar');

        $this->assertEquals($comm->toString(), 'docker run -e \'FOO=bar\'');
    }
}
