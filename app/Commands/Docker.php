<?php

namespace App\Commands;

use App\Process;
use App\Commands\Traits\HasDynamicArgs;
use LaravelZero\Framework\Commands\Command;
use App\CommandExecutor;
use App\Builder\Docker as DockerBuilder;
use App\Builder\Unescaped;

class Docker extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'docker';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run docker directly.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(CommandExecutor $executor)
    {
        return $executor->run(DockerBuilder::make($this->getArgs()));
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
