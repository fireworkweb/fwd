<?php

namespace App\Commands;

use App\Commands\Traits\HasDynamicArgs;
use App\Process;
use LaravelZero\Framework\Commands\Command;

class DockerCompose extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'docker-compose';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run docker-compose directly.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Process $process)
    {
        $process->dockerCompose($this->getArgs());
    }

    /**
     * Get default args when empty.
     *
     * @return string
     */
    public function getDefaultArgs(): string
    {
        return 'ps';
    }
}
