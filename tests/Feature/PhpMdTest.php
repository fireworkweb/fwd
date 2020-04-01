<?php

namespace Tests\Feature;

use Tests\TestCase;

class PhpMdTest extends TestCase
{
    public function testPhpMd()
    {
        $this->artisan('phpmd')->assertExitCode(0);

        $this->assertDockerRun('jakzal/phpqa:1.34-alpine phpmd app/ text phpmd/codesize.xml,phpmd/controversial.xml,phpmd/design.xml,phpmd/naming.xml,unusedcode,phpmd/cleancode.xml');
    }

    public function testPhpMdCustom()
    {
        $this->artisan('phpmd something')->assertExitCode(0);

        $this->assertDockerRun('jakzal/phpqa:1.34-alpine phpmd something');
    }
}
