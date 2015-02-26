<?php

namespace Sham\TestDouble;

use Sham\TestDouble\Stub;

class StubTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function smoke_test()
    {
        $stub = new Stub;
        $stub->stub('foo')->toReturn(123);
        $stub->stub('bar')->with(123)->toReturn(123);
        $stub->stub('baz');
    }

    /** @test */
    public function it_throws_for_unknown_method_calls()
    {
        $stub = new Stub;

        $this->setExpectedException("Exception");
        $stub->foo();
    }

    /** @test */
    public function it_allows_us_to_ignore_all_calls()
    {
        $stub = new Stub;
        $stub->shouldIgnoreMissing();
        $stub->foo();
    }

    /** @test */
    public function it_returns_a_stubbed_value()
    {
        $stub = new Stub();

        $stub->stub('foo')->toReturn('bar');
        $stub->stub('baz')->toReturn(123);

        $this->assertSame('bar', $stub->foo());
        $this->assertSame(123, $stub->baz());
    }

    /** @test */
    public function it_returns_a_stubbed_value_from_the_right_args()
    {
        $stub = new Stub;

        $stub->stub('foo')->with('bar')->toReturn('baz');
        $stub->stub('foo')->with(123)->toReturn(456);
        $stub->stub('foo')->with(123, 456)->toReturn(789);

        $this->assertSame('baz', $stub->foo('bar'));
        $this->assertSame(456, $stub->foo(123));
        $this->assertSame(789, $stub->foo(123, 456));
    }


    /** @test */
    public function it_throws_for_known_method_names_with_incorrect_args()
    {
        $stub = new Stub;

        $stub->stub('foo')->with('bar');

        $this->setExpectedException("Exception");

        $stub->foo('baz');
    }

    /** @test */
    public function it_does_not_throw_for_known_method_names_width_incorrect_args_if_ignoring_missing()
    {
        $stub = new Stub;
        $stub->shouldIgnoreMissing();

        $stub->stub('foo')->with('bar');

        $this->assertNull($stub->foo('baz'));
    }

    /** @test */
    public function smoke_test_with_methods()
    {
        $stub = new Stub;

        $stub->stub('foo')->withArgs(['bar'])->toReturn('baz');
        $stub->stub('foo')->withArgs(['bar', 'baz'])->toReturn('bazbaz');
        $stub->stub('foo')->withNoArgs()->toReturn(123);

        $this->assertSame('baz', $stub->foo('bar'));
        $this->assertSame('bazbaz', $stub->foo('bar', 'baz'));
        $this->assertSame(123, $stub->foo());
    }

    /** @test */
    public function it_returns_a_stubbed_value_for_any_args()
    {
        $stub = new Stub;

        $stub->stub('foo')->withAnyArgs()->toReturn('bar');

        $this->assertSame('bar', $stub->foo());
        $this->assertSame('bar', $stub->foo(123));
        $this->assertSame('bar', $stub->foo('baz'));
    }

    /** @test */
    public function it_returns_a_stubbed_value_for_the_most_recent_match()
    {
        $stub = new Stub;

        $stub->stub('foo')->toReturn('bar');
        $stub->stub('foo')->toReturn('baz');

        $this->assertSame('baz', $stub->foo());
    }
}


