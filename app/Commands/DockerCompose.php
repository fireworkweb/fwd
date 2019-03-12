<?php

namespace App\Commands;

use App\Commands\Traits\HasDynamicArgs;
use App\Commands\Traits\Process;
use LaravelZero\Framework\Commands\Command;

class DockerCompose extends Command
{
    use HasDynamicArgs, Process;

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
    public function handle()
    {
        $this->dockerCompose($this->getArgs());
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
