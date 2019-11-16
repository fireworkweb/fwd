<?php

namespace Tests\Unit;

use App\Builder\Argument;
use App\Builder\Builder;
use App\Builder\Escaped;
use App\Builder\Unescaped;
use Tests\TestCase;

class ArgumentTest extends TestCase
{
    public function testArgumentOnlyName()
    {
        $arg = new Argument(Escaped::make('foo'));

        $this->assertEquals('\'foo\'', (string) $arg);

        $arg = new Argument('foo');

        $this->assertEquals('foo', (string) $arg);
    }

    public function testArgumentWithNameAndValue()
    {
        $arg = new Argument('foo', 'bar');

        $this->assertEquals((string) $arg, 'foo=\'bar\'');
    }

    public function testArgumentWithNameAndValueNoSeparator()
    {
        $arg = new Argument('foo', 'bar', ' ');

        $this->assertEquals((string) $arg, 'foo \'bar\'');
    }

    public function testArgumentWithCommand()
    {
        $arg = new Argument(new Builder('foo'));

        $this->assertEquals((string) $arg, 'foo');
    }

    public function testArgumentWithCommandWithArgument()
    {
        $arg = new Argument(new Builder('foo', new Argument('--bar')));

        $this->assertEquals((string) $arg, 'foo --bar');
    }

    public function testArgumentWithValueAsArgument()
    {
        $argFoo = new Argument('FOO', 'bar');

        $this->assertEquals((string) $argFoo, 'FOO=\'bar\'');

        $argFoo = new Argument('FOO', Unescaped::make('bar'));
        $argEnv = new Argument('--env', (string) $argFoo);

        $this->assertEquals((string) $argFoo, 'FOO=bar');
        $this->assertEquals('--env=\'FOO=bar\'', (string) $argEnv);
    }

    public function testArgumentWithValueAsUnscaped()
    {
        $arg = new Argument('--foo', Unescaped::make('bar'), ' ');

        $this->assertEquals((string) $arg, '--foo bar');
    }
}
