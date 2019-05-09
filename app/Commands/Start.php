<?php

namespace App\Commands;

use App\Commands\Traits\RunTask;
use App\Commands\Traits\ArtisanCall;
use LaravelZero\Framework\Commands\Command;

class Start extends Command
{
    use ArtisanCall, RunTask;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'start
                            {--no-wait : Do not wait for Docker and MySQL to become available}
                            {--timeout=60 : The number of seconds to wait}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Start fwd environment containers.';

    protected $seconds = 0;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $commands = [
            [$this, 'dockerComposePs'],
            [$this, 'dockerComposeUpD'],
            [$this, 'mysql'],
        ];

        // Run commands, first that isn't success (0) stops and return that exitCode
        foreach ($commands as $command) {
            if ($exitCode = call_user_func($command)) {
                return $exitCode;
            }
        }
    }

    protected function dockerComposePs()
    {
        return $this->runTask('Checking Docker', function() {
            return $this->runCommand(function () {
                return $this->artisanCallNoOutput('ps');
            });
        });
    }

    protected function dockerComposeUpD()
    {
        return $this->runTask('Starting fwd', function() {
            return $this->artisanCall('up', ['-d']);
        });
    }

    protected function mysql()
    {
        return $this->runTask('Checking MySQL', function() {
            return $this->runCommand(function () {
                return $this->artisanCallNoOutput('mysql-raw', ['-e', 'SELECT 1']);
            });
        });
    }

    protected function runCommand(\Closure $closure)
    {
        return ! $this->option('no-wait')
            ? $this->waitForCommand($closure)
            : $closure();
    }

    protected function waitForCommand(\Closure $closure)
    {
        while ($exitCode = $closure()) {
            if ($this->seconds++ > $this->option('timeout')) {
                $this->error('Timed out waiting the command to finish');

                return 1;
            }

            sleep(1);
        }

        return $exitCode;
    }
}
