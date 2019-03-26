<?php

namespace Tests\Feature;

use Tests\TestCase;

class JsInspectTest extends TestCase
{
    public function testJsInspect()
    {
        $this->artisan('jsinspect')->assertExitCode(0);

        $this->assertDockerRun('fireworkweb/node:qa jsinspect src/');
    }

    public function testJsInspectCustom()
    {
        $this->artisan('jsinspect app/')->assertExitCode(0);

        $this->assertDockerRun("fireworkweb/node:qa jsinspect 'app/'");
    }
}
