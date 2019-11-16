<?php

namespace Tests\Unit;

use App\Builder\Artisan;
use Tests\TestCase;

class ArtisanCommandTest extends TestCase
{
    public function testArtisan()
    {
        $artisan = new Artisan();

        $this->assertEquals($this->makeDockerComposeExecUserString(env('FWD_ASUSER'), 'app php artisan'), (string) $artisan);
    }

    public function testArtisanTinker()
    {
        $comm = new Artisan('tinker');

        $this->assertEquals($this->makeDockerComposeExecUserString(env('FWD_ASUSER'), 'app php artisan tinker'), (string) $comm);
    }
}
