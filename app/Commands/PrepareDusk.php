<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use Illuminate\Support\Facades\Artisan;
use LaravelZero\Framework\Providers\CommandRecorder\CommandRecorderRepository;

class PrepareDusk extends Command
{
    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'prepare-dusk';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a test dedicated database named dusk.';

    public function call($command, array $arguments = [])
    {
        resolve(CommandRecorderRepository::class)->create($command, $arguments);

        return Artisan::call($command, $arguments);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $DB_USERNAME = env('DB_USERNAME');

        $this->call('mysql', ['-e', 'drop database if exists dusk']);
        $this->call('mysql', ['-e', 'create database dusk']);
        $this->call('mysql', ['-e', "grant all on dusk.* to $DB_USERNAME@\"%\""]);
        $this->call('artisan', ['migrate:fresh', '--seed', '--database=dusk']);

    }
}
