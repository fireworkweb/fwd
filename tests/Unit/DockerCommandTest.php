<?php

namespace Tests\Unit;

use App\Builder\Docker;
use App\Builder\Escaped;
use Tests\TestCase;

class DockerCommandTest extends TestCase
{
    public function testDocker()
    {
        $comm = new Docker();

        $this->assertEquals('docker', (string) $comm);
    }

    public function testDockerInnerCommand()
    {
        $comm = new Docker('run', '-e', Escaped::make('FOO=bar'));

        $this->assertEquals('docker run -e \'FOO=bar\'', (string) $comm);
    }
}
