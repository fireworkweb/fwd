<?php

namespace App\Providers;

use App\Checker;
use App\CommandExecutor;
use App\Environment;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\ViewServiceProvider;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Checker::class);
        $this->app->singleton(Environment::class);
        $this->app->singleton(CommandExecutor::class);

        $this->registerView();
        $this->loadFwd();
    }

    protected function registerView()
    {
        $this->app['config']->set('view', [
            'paths' => [getcwd()],
            'compiled' => resolve(Environment::class)->getConfigDirFolder('views'),
        ]);

        $this->app->register(ViewServiceProvider::class);
    }

    protected function loadFwd()
    {
        app(Environment::class)->load();

        if ($commands = $this->getCustomCommands()) {
            $this->commands($commands);
        }
    }

    protected function getCustomCommands()
    {
        if (! is_dir(env('FWD_CUSTOM_PATH'))) {
            return;
        }

        return collect((new Finder())->in(env('FWD_CUSTOM_PATH'))->files())
            ->map(function (SplFileInfo $file) {
                return $file->getPathname();
            })
            ->filter(function ($path) {
                return $this->validateCustomCommand($path);
            })
            ->each(function ($path) {
                require_once $path;
            })
            ->map(function ($path) {
                return pathinfo($path, PATHINFO_FILENAME);
            })
            ->filter(function ($file) {
                return is_subclass_of($file, Command::class);
            })
            ->values()
            ->toArray();
    }

    protected function validateCustomCommand(string $path): bool
    {
        $autoloadPath = base_path('vendor/autoload.php');

        $fileContent = implode(PHP_EOL, [
            '<?php',
            "require '{$autoloadPath}';",
            "require_once '{$path}';",
        ]);

        $hash = Str::random(10);
        $fileName = "fwd-{$hash}.php";
        $filePath = "/tmp/{$fileName}";

        file_put_contents($filePath, $fileContent);

        $pipes = [];

        $proc = proc_open("php {$filePath}", [
            ['pipe', 'r'],
            ['pipe', 'w'],
            ['pipe', 'w'],
        ], $pipes);

        $exitCode = proc_close($proc);
        $isValid = 0 === $exitCode;

        unlink($filePath);

        if (! $isValid) {
            echo implode(' ', [
                'FAILED COMMAND:',
                pathinfo($path, PATHINFO_FILENAME),
                "({$path})",
                PHP_EOL,
            ]);
        }

        return $isValid;
    }
}
