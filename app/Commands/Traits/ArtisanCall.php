<?php

namespace App\Commands\Traits;

use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Providers\CommandRecorder\CommandRecorderRepository;
use App\Process;

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
        $process = app(Process::class);
        $process->disableOutput();
        $exitCode = $this->artisanCall($command, $arguments);
        $process->enableOutput();

        return $exitCode;
    }
}
