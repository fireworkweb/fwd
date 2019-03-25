<?php

namespace App\Commands\Traits;

use LaravelZero\Framework\Providers\CommandRecorder\CommandRecorderRepository;
use Illuminate\Support\Facades\Artisan;


trait ArtisanCall
{
    public function artisanCall($command, array $arguments = [])
    {
        $arguments = array_filter($arguments);

        resolve(CommandRecorderRepository::class)->create($command, $arguments);

        return Artisan::call($command, $arguments);
    }
}
