<?php

namespace App\Commands;

use App\Builder\Builder;
use Symfony\Component\Yaml\Yaml;

class Script extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'script {name}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run fwd script.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $fwdYaml = Yaml::parseFile(
            $this->environment->getContextFile('fwd.yaml'),
            Yaml::PARSE_OBJECT_FOR_MAP
        );

        if (! $script = object_get($fwdYaml, 'scripts.'.$this->argument('name'))) {
            $this->error('Script not found.');

            return 1;
        }

        try {
            collect($script)
                ->each(function ($command) {
                    $exitCode = $this->runTask($command, function () use ($command) {
                        return $this->commandExecutor->runQuietly(
                            Builder::make($command)
                        );
                    });

                    if ($exitCode) {
                        throw new \Exception("Command '{$command}' failed.");
                    }
                });
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());

            return 1;
        }
    }
}
