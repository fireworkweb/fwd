<?php

namespace Tests\Unit;

use App\Builder\Generic;
use App\Events\BeforeExecuteCommand;
use Tests\TestCase;

class BeforeExecuteCommandTest extends TestCase
{
    public function testBeforeExecuteCommand()
    {
        $command = new Generic('foo');
        $beforeExecuteCommandEvent = new BeforeExecuteCommand($command);

        $this->assertEquals($command, $beforeExecuteCommandEvent->getCommand());
    }
}
