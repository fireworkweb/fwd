<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Builder\Docker;
use App\Builder\Unescaped;
use App\Builder\Escaped;

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
