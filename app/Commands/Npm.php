<?php

namespace App\Commands;

use App\Builder\Npm as NpmBuilder;
use App\Commands\Traits\HasDynamicArgs;

class Npm extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'npm';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run npm in a new container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->commandExecutor->run(
            NpmBuilder::make($this->getArgs())
        );
    }

    /**
     * Get default args when empty.
     *
     * @return string
     */
    public function getDefaultArgs(): string
    {
        return '-v';
    }
}
