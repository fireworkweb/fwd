<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Builder\Artisan;
use App\Builder\Unescaped;

class ArtisanCommandTest extends TestCase
{
    public function testArtisan()
    {
        $artisan = new Artisan();

        $this->assertEquals((string) $artisan, $this->makeDockerComposeExecUserString(env('FWD_ASUSER'), 'app php artisan'));
    }

    public function testArtisanTinker()
    {
        $comm = new Artisan('tinker');

        $this->assertEquals($this->makeDockerComposeExecUserString(env('FWD_ASUSER'), 'app php artisan tinker'), (string) $comm);
    }
}
