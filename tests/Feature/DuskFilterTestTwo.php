<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Commands\Dusk;

/**
 * @see Dusk
 */
class DuskFilterTestTwo extends TestCase
{
    public function dusk()
    {
        Dusk::class;
    }
}
