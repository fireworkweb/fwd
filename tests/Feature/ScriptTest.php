<?php

namespace Tests\Feature;

use App\Environment;
use Mockery\MockInterface;
use Tests\TestCase;

class ScriptTest extends TestCase
{
    public function testScript1()
    {
        $this->mockFwdYaml();

        $this->artisan('script script1')->assertExitCode(0);

        $this->assertCommandRun(['cmd1']);
        $this->assertCommandRun(['cmd2']);
    }

    public function testScript2()
    {
        $this->mockFwdYaml();

        $this->artisan('script script2')->assertExitCode(0);

        $this->assertCommandRun(['cmd3']);
        $this->assertCommandRun(['cmd4']);
    }

    protected function mockFwdYaml()
    {
        $this->mock(Environment::class, function (MockInterface $mock) {
            $mock->shouldReceive('getContextFile')
                ->withArgs(['fwd.yaml'])
                ->andReturn(base_path('tests/fixtures/fwd.yaml'));
        });
    }
}
