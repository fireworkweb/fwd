<?php

namespace Tests\Feature;

use Tests\TestCase;

class DuskTest extends TestCase
{
    public function testDusk()
    {
        $this->artisan('dusk')->assertExitCode(0);

        $this->asFWDUser()->assertDockerComposeExec('app php artisan dusk');
    }

    public function testDuskFilter()
    {
        $this->artisan('dusk', [
            '--files' => 'app/Commands/Dusk.php',
            '--dir' => 'tests/Feature',
        ])->assertExitCode(0);

        $this->asFWDUser()->assertDockerComposeExec('app php artisan dusk --filter=\'Tests\Feature\DuskFilterTestOne|Tests\Feature\DuskFilterTestTwo\'');
    }
}
