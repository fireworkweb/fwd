<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Builder\Builder;
use App\Events\BeforeExecuteCommand;

class BeforeExecuteCommandTest extends TestCase
{
    public function testBeforeExecuteCommand()
    {
        $command = new Builder('foo');
        $beforeExecuteCommandEvent = new BeforeExecuteCommand($command);

        $this->assertEquals($command, $beforeExecuteCommandEvent->getCommand());
    }
}
