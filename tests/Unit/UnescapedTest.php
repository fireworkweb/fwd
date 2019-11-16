<?php

namespace Tests\Unit;

use App\Builder\Unescaped;
use Tests\TestCase;

class UnescapedTest extends TestCase
{
    public function testUnescapedConstructor()
    {
        $comm = new Unescaped('foo');

        $this->assertEquals($comm->__toString(), 'foo');
    }

    public function testUnescapedStatic()
    {
        $comm = Unescaped::make('foo');

        $this->assertEquals($comm->__toString(), 'foo');
    }

    public function testUnescapedIsIndeedNotEscaped()
    {
        $comm = Unescaped::make('foo bar');

        $this->assertEquals($comm->__toString(), 'foo bar');
    }
}
