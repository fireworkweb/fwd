<?php


namespace Tests\Feature;


use Tests\TestCase;
use App\Process;


class PrepareDuskTest extends TestCase
{
    public function testDusk()
    {
        $DB_USERNAME = env('DB_USERNAME');

        $this->mockProcess();

        $this->artisan('prepare-dusk')->assertExitCode(0);

        $this->assertCommandCalled('mysql', ['-e', 'drop database if exists dusk']);
        $this->assertCommandCalled('mysql', ['-e', 'create database dusk']);
        $this->assertCommandCalled('mysql', ['-e', "grant all on dusk.* to $DB_USERNAME@\"%\""]);
        $this->assertCommandCalled('artisan', ['migrate:fresh', '--seed', '--database=dusk']);
    }
}
