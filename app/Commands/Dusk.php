<?php

namespace App\Commands;

use App\Commands\Traits\ArtisanCall;
use App\Commands\Traits\HasDynamicArgs;
use App\Process;
use Brick\Reflection\ImportResolver;
use Illuminate\Support\Collection;
use LaravelZero\Framework\Commands\Command;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

class Dusk extends Command
{
    use ArtisanCall, HasDynamicArgs;

    /**
     * The name of the command.
     *
     * @var string
     */
    protected $name = 'dusk';

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'dusk
                            {--f|files= : List of files to find respective test separated by space}
                            {--d|dir : Test files directory}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run artisan dusk commands inside the Application container.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Process $process)
    {
        $changedFiles = collect(explode(' ', $this->option('files')))
            ->map(function ($file) {
                include($file);

                return end(get_declared_classes());
            });

        if (! $changedFiles) {
            return $process->asFWDUser()->dockerComposeExec('app php artisan dusk', $this->getArgs());
        }

        $path = base_path($this->option('dir'));
        $files = Finder::create()
            ->files()
            ->in($path)
            ->name('*.php');

        $filter = collect($files)
            ->map(function ($file) {
                include($file);

                return end(get_declared_classes());
            })
            ->filter(function ($class) use ($changedFiles) {

                return $this->getClassAnnotations($class)
                    ->reduce(function ($carry, $item) use ($changedFiles) {
                        return $carry || $changedFiles->contains($item);
                    });
            })
            ->join(' ');

        return $process->asFWDUser()->dockerComposeExec('app php artisan dusk',
            array_merge(
                $this->getArgs(),
                ['--filter' => $filter]
            )
        );
    }

    protected function getClassAnnotations($class): Collection
    {
        $class = new ReflectionClass($class);
        $resolver = new ImportResolver($class);
        $comment_string = $class->getDocComment();

        preg_match_all('#(?:@see\s*)(?P<class>[a-zA-Z0-9, ()_].*)#', $comment_string, $matches, PREG_PATTERN_ORDER);

        return collect($matches['class'])
            ->map(function ($class) use ($resolver) {
                return $resolver->resolve($class);
            });
    }
}
