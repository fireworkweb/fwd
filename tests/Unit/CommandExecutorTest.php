<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Builder\Command;
use App\CommandExecutor;

class CommandExecutorTest extends TestCase
{
    public function testCommandExecutorRun()
    {
        $commExec = resolve(CommandExecutor::class);

        $this->assertEquals(0, $commExec->run(new Command('foo')));

        $this->assertCommandRun(['foo']);
    }

    public function testCommandExecutorRunQuietly()
    {
        $commExec = resolve(CommandExecutor::class);

        $this->assertEquals(0, $commExec->runQuietly(new Command('foo')));

        $this->assertCommandRun(['foo']);
    }

    public function testCommandExecutorOutputNotEmpty()
    {
        $commExec = new CommandExecutor();
        $commExec->runQuietly(new Command('echo 1'));

        $this->assertNotEmpty($commExec->getOutputBuffer());
    }

    public function testCommandExecutorOutputEmpty()
    {
        $commExec = new CommandExecutor();
        $commExec->runQuietly(new Command('echo'));

        $this->assertEmpty($commExec->getOutputBuffer());
    }
}
