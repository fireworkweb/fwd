<?php

namespace Tests\Feature;

use App\Commands\Dusk;
use Tests\TestCase;

/**
 * @see Dusk
 */
class DuskFilterTestOne extends TestCase
{

    public function dusk()
    {
        Dusk::class;
    }

}
