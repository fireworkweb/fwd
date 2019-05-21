<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Builder\Unescaped;

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
