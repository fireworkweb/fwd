<?php

namespace Tests\Feature;

use Tests\TestCase;

class PhanTest extends TestCase
{
    public function testPhan()
    {
        $this->artisan('phan')->assertExitCode(0);

        $this->assertDockerRun('jakzal/phpqa:alpine phan --color -p -l app -iy 5');
    }

    public function testPhanCustom()
    {
        $this->artisan('phan something')->assertExitCode(0);

        $this->assertDockerRun('jakzal/phpqa:alpine phan something');
    }
}
