<?php

namespace App\Tasks;

use App\Builder\Builder;
use App\Environment;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Deploy extends Task
{
    const URL = 'https://fwd.tools';

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
        } catch (RequestException $exception) {
            $this->command->error("Deploy failed");

            if ($exception->response->status() === 422) {
                $this->command->error(
                    collect($exception->response->object()->errors)->flatten()->implode(PHP_EOL)
                );
            }

            return 1;
        } catch (\Exception $exception) {
            $this->command->error("Deploy failed");
            $this->command->error($exception->getMessage());

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
            $file = fopen($environment->getContextFile($this->file), 'r');
            $data = ['slug' => env('FWD_TOOLS_SLUG')];

            $this->deploy = $this->http()
                ->attach('deploy', $file, 'deploy.tgz')
                ->post(self::URL . '/api/deploy/create', $data)
                ->throw()
                ->object();

            return 0;
        });
    }

    public function building() : int
    {
        return $this->runTask('Building', function () {
            return $this->runCallableWaitFor(function () {
                $this->deploy = $this->http()
                    ->get(self::URL . sprintf('/api/deploy/%s/status', $this->deploy->id))
                    ->throw()
                    ->object();

                if ($this->deploy->status === 'failed') {
                    throw new \Exception('Failed');
                }

                return $this->deploy->status === 'success' ? 0 : 1;
            }, 600);
        });
    }

    protected function http()
    {
        return Http::withToken(env('FWD_TOOLS_TOKEN'))
            ->withHeaders(['Accept' => 'application/json']);
    }
}
