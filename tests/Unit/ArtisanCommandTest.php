<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Builder\Artisan;

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
