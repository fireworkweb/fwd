<?php

namespace App\Commands\Traits;

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
}
