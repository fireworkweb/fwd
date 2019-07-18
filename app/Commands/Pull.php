<?php

namespace App\Commands;

use App\Builder\Docker;
use App\Commands\Traits\HasDynamicArgs;

class Pull extends Command
{
    use HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'pull';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Pull all containers used by fwd.';

    public function handle()
    {
        $callable = [$this, 'pullDockerImage'];

        return $this->runCommands([
            [$callable, ['app', env('FWD_IMAGE_APP')]],
            [$callable, ['php-qa', env('FWD_IMAGE_PHP_QA')]],
            [$callable, ['node', env('FWD_IMAGE_NODE')]],
            [$callable, ['node-qa', env('FWD_IMAGE_NODE_QA')]],
            [$callable, ['cache', env('FWD_IMAGE_CACHE')]],
            [$callable, ['database', env('FWD_IMAGE_DATABASE')]],
        ]);
    }

    protected function pullDockerImage($name, $image)
    {
        return $this->runTask("Pulling image for {$name}", function () use ($image) {
            return $this->commandExecutor->runQuietly(
                Docker::make('pull', $image, $this->getArgs())
            );
        });
    }
}
