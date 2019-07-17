<?php

namespace App\Commands\Traits;

use App\Process;
use App\CommandExecutor;
use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Providers\CommandRecorder\CommandRecorderRepository;

trait ArtisanCall
{
    public function artisanCall($command, array $arguments = [])
    {
        $arguments = array_filter($arguments);

        resolve(CommandRecorderRepository::class)->create($command, $arguments);

        return Artisan::call($command, $arguments);
    }

    public function artisanCallNoOutput($command, array $arguments = [])
    {
        $process = app(Process::class)->disableOutput();
        $commandExecutor = app(CommandExecutor::class)->disableOutput();

        $exitCode = $this->artisanCall($command, $arguments);

        $process->enableOutput();
        $commandExecutor->enableOutput();

        if ($exitCode) {
            if ($output = $process->getOutputBuffer()) {
                $process->print($output);
            }

            if ($output = $commandExecutor->getOutputBuffer()) {
                $commandExecutor->print($output);
            }
        }

        return $exitCode;
    }
}
