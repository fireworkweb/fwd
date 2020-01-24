<?php

namespace Tests\Unit;

use App\Builder\Generic;
use App\CommandExecutor;
use Tests\TestCase;

class CommandExecutorTest extends TestCase
{
    public function testCommandExecutorRun()
    {
        $commExec = resolve(CommandExecutor::class);

        $this->assertEquals(0, $commExec->run(new Generic('foo')));

        $this->assertCommandRun(['foo']);
    }

    public function testCommandExecutorRunQuietly()
    {
        $commExec = resolve(CommandExecutor::class);

        $this->assertEquals(0, $commExec->runQuietly(new Generic('foo')));

        $this->assertCommandRun(['foo']);
    }

    public function testCommandExecutorOutputNotEmpty()
    {
        $commExec = new CommandExecutor();
        $commExec->runQuietly(new Generic('echo 1'));

        $this->assertNotEmpty($commExec->getOutputBuffer());
    }

    public function testCommandExecutorOutputEmpty()
    {
        $commExec = new CommandExecutor();
        $commExec->runQuietly(new Generic('echo'));

        $this->assertEmpty($commExec->getOutputBuffer());
    }
}
