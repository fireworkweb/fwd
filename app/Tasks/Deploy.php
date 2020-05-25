<?php

namespace App\Tasks;

use App\Builder\Builder;
use App\Environment;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Deploy extends Task
{
    const URL = 'http://fwd.tools';

    protected $file;
    protected $deploy;

    public function run(...$args): int
    {
        try {
            $commands = [
                [$this, 'createReleaseFile'],
                [$this, 'sendReleaseFile'],
                [$this, 'building'],
            ];

            if ($exitCode = $this->runCallables($commands)) {
                return $exitCode;
            }

            $this->command->info('Access URL: ' . $this->deploy->url);

            return 0;
        } catch (\Exception $e) {
            $this->command->error("Deploy failed: {$e->getMessage()}");

            return 1;
        } finally {
            $environment = resolve(Environment::class);
            File::delete($environment->getContextFile($this->file));
        }
    }

    public function createReleaseFile() : int
    {
        return $this->runTask('Create Release File', function () {
            $this->file = sprintf('%s.tgz', Str::random(10));

            $this->runCommandWithoutOutput(
                Builder::make('git', 'archive', '--format=tar.gz', 'HEAD', '-o', $this->file)
            );

            return 0;
        });
    }

    public function sendReleaseFile() : int
    {
        return $this->runTask('Send Release File', function () {
            $environment = resolve(Environment::class);

            $this->deploy = Http::attach(
                'deploy',
                fopen($environment->getContextFile($this->file), 'r'),
                'deploy.tgz'
            )->post(self::URL . '/api/deploy/create')->throw()->object();

            return 0;
        });
    }

    public function building() : int
    {
        return $this->runTask('Building', function () {
            return $this->runCallableWaitFor(function () {
                $url = self::URL . sprintf('/api/deploy/%s/status', $this->deploy->id);
                $this->deploy = Http::get($url)->throw()->object();


                if ($this->deploy->status === 'failed') {
                    throw new \Exception('Failed');
                }

                return $this->deploy->status === 'success' ? 0 : 1;
            }, 600);
        });
    }
}
