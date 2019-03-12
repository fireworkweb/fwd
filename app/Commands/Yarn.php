<?php

namespace App\Commands;

use App\Commands\Traits\HasDynamicArgs;
use App\Commands\Traits\Process;
use LaravelZero\Framework\Commands\Command;

class Yarn extends Command
{
    use HasDynamicArgs, Process;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'yarn';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run yarn in a new container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->dockerRun(env('FWD_IMAGE_NODE'), 'yarn', $this->getArgs());
    }

    /**
     * Get default args when empty.
     *
     * @return string
     */
    public function getDefaultArgs(): string
    {
        return 'yarn -v';
    }
}
