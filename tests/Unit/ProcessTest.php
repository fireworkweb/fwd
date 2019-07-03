<?php

namespace Tests\Unit;

use App\Process;
use Tests\TestCase;

class ProcessTest extends TestCase
{
    public function testProcess()
    {
        $process = resolve(Process::class);

        $process->process(['foo']);

        $this->assertProcessRun(['foo']);
    }

    public function testProcessNoOutput()
    {
        $process = resolve(Process::class);

        $process->dockerNoOutput('ps');

        $this->assertDocker('ps');
    }

    public function testProcessOutputNotEmpty()
    {
        $process = new Process();

        $process->dockerNoOutput('ps');

        $this->assertNotEmpty($process->getOutputBuffer());
    }

    public function testProcessOutputEmpty()
    {
        $process = new Process();

        $process->docker('ps');

        $this->assertEmpty($process->getOutputBuffer());
    }
}
