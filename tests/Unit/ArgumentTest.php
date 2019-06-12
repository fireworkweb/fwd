<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Builder\Command;
use App\Builder\Argument;
use App\Builder\Unescaped;
use App\Builder\Escaped;

class ArgumentTest extends TestCase
{
    public function testArgumentOnlyName()
    {
        $arg = new Argument(Escaped::make('foo'));

        $this->assertEquals('\'foo\'', $arg->__toString());

        $arg = new Argument('foo');

        $this->assertEquals('foo', $arg->__toString());
    }

    public function testArgumentWithNameAndValue()
    {
        $arg = new Argument('foo', 'bar');

        $this->assertEquals($arg->__toString(), 'foo=\'bar\'');
    }

    public function testArgumentWithNameAndValueNoSeparator()
    {
        $arg = new Argument('foo', 'bar', ' ');

        $this->assertEquals($arg->__toString(), 'foo \'bar\'');
    }

    public function testArgumentWithCommand()
    {
        $arg = new Argument(new Command('foo'));

        $this->assertEquals($arg->__toString(), 'foo');
    }

    public function testArgumentWithCommandWithArgument()
    {
        $arg = new Argument(new Command('foo', new Argument('--bar')));

        $this->assertEquals($arg->__toString(), 'foo --bar');
    }

    public function testArgumentWithValueAsArgument()
    {
        $argFoo = new Argument('FOO', 'bar');

        $this->assertEquals($argFoo->__toString(), 'FOO=\'bar\'');

        $argFoo = new Argument('FOO', Unescaped::make('bar'));
        $argEnv = new Argument('--env', (string) $argFoo);

        $this->assertEquals($argFoo->__toString(), 'FOO=bar');
        $this->assertEquals('--env=\'FOO=bar\'', $argEnv->__toString());
    }

    public function testArgumentWithValueAsUnscaped()
    {
        $arg = new Argument('--foo', Unescaped::make('bar'), ' ');

        $this->assertEquals($arg->__toString(), '--foo bar');
    }
}
