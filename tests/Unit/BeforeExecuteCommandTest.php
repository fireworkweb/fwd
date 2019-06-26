<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Events\BeforeExecuteCommand;
use App\Builder\Command;

class BeforeExecuteCommandTest extends TestCase
{
    public function testBeforeExecuteCommand()
    {
        $command = new Command('foo');
        $beforeExecuteCommandEvent = new BeforeExecuteCommand($command);

        $this->assertEquals($command, $beforeExecuteCommandEvent->getCommand());
    }
}
