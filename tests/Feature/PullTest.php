<?php

namespace Tests\Feature;

use Tests\TestCase;

class PullTest extends TestCase
{
    public function testPull()
    {
        $this->artisan('pull')->assertExitCode(0);

        $images = [
            env('FWD_IMAGE_APP'),
            env('FWD_IMAGE_PHP_QA'),
            env('FWD_IMAGE_NODE'),
            env('FWD_IMAGE_NODE_QA'),
            env('FWD_IMAGE_CACHE'),
            env('FWD_IMAGE_DATABASE'),
        ];

        foreach ($images as $image) {
            $this->assertDocker("pull '{$image}'");
        }
    }
}
