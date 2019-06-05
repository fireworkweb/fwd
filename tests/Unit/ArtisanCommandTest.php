<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Builder\Artisan;
use App\Builder\Unescaped;

class ArtisanCommandTest extends TestCase
{
    public function testArtisan()
    {
        $comm = new Artisan();

        $this->assertEquals($comm->toString(), 'docker-compose -p fwd exec -T --user '.env('FWD_ASUSER').' app php artisan');
    }

    public function testArtisanTinker()
    {
        $comm = new Artisan(Unescaped::make('tinker'));

        $this->assertEquals($comm->toString(), 'docker-compose -p fwd exec -T --user '.env('FWD_ASUSER').' app php artisan tinker');
    }
}
