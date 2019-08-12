<?php

namespace App\Commands;

use App\Tasks\PhpMnd as PhpMndTask;
use App\Commands\Traits\HasDynamicArgs;

class PhpMnd extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'phpmnd';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run phpmnd in the PHP-QA container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        return PhpMndTask::make($this)->run($this->getArgs());
    }
}
