<?php

namespace Sham\TestDouble;

use Sham\TestDouble\Stub;

class StubTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function smoke_test()
    {
        $stub = new StubImpl;
        $stub->stub('foo')->toReturn(123);
        $stub->stub('bar')->with(123)->toReturn(123);
        $stub->stub('baz');
    }

    /** @test */
    public function it_throws_for_unknown_method_calls()
    {
        $stub = new StubImpl;

        $this->setExpectedException("Exception");
        $stub->foo();
    }

    /** @test */
    public function it_allows_us_to_ignore_all_calls()
    {
        $stub = new StubImpl;
        $stub->shouldIgnoreMissing();
        $stub->foo();
    }

    /** @test */
    public function it_returns_a_stubbed_value()
    {
        $stub = new StubImpl();

        $stub->stub('foo')->toReturn('bar');
        $stub->stub('baz')->toReturn(123);

        $this->assertSame('bar', $stub->foo());
        $this->assertSame(123, $stub->baz());
    }

    /** @test */
    public function it_returns_a_stubbed_value_from_the_right_args()
    {
        $stub = new StubImpl;

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
        $stub = new StubImpl;

        $stub->stub('foo')->with('bar');

        $this->setExpectedException("Exception");

        $stub->foo('baz');
    }

    /** @test */
    public function it_does_not_throw_for_known_method_names_width_incorrect_args_if_ignoring_missing()
    {
        $stub = new StubImpl;
        $stub->shouldIgnoreMissing();

        $stub->stub('foo')->with('bar');

        $this->assertNull($stub->foo('baz'));
    }

    /** @test */
    public function smoke_test_with_methods()
    {
        $stub = new StubImpl;

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
        $stub = new StubImpl;

        $stub->stub('foo')->withAnyArgs()->toReturn('bar');

        $this->assertSame('bar', $stub->foo());
        $this->assertSame('bar', $stub->foo(123));
        $this->assertSame('bar', $stub->foo('baz'));
    }

    /** @test */
    public function it_returns_a_stubbed_value_for_the_most_recent_match()
    {
        $stub = new StubImpl;

        $stub->stub('foo')->toReturn('bar');
        $stub->stub('foo')->toReturn('baz');

        $this->assertSame('baz', $stub->foo());
    }
}

class StubImpl implements Stub
{
    private $shouldIgnoreMissing = false;
    private $stubMethods = [];

    /**
     * @param  string   $method   The method to stub
     * @return StubMethod
     */
    public function stub($method)
    {
        $stubMethod = new StubMethodImpl($method);

        $this->stubMethods[] = $stubMethod;

        return $stubMethod;
    }

    /**
     * @return void
     */
    public function shouldIgnoreMissing()
    {
        $this->shouldIgnoreMissing = true;
    }

    public function __call($methodName, $args)
    {
        foreach (array_reverse($this->stubMethods) as $stubMethod) {
            if ($stubMethod->matches($methodName, $args)) {
                return $stubMethod->getReturnValue();
            }
        }

        if ($this->shouldIgnoreMissing) {
            return;
        }

        throw new \Exception("Method not found");
    }
}

class StubMethodImpl implements StubMethod
{
    private $returnValue;
    private $name;
    private $args = [];
    private $anyArgs = false;

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $arg     The first expected argument
     * @param mixed $arg,... The subsequent expected arguments
     *
     * @return StubMethodWithArguments
     */
    public function with($arg/*, $arg2..., $arg3...*/)
    {
        $this->args = func_get_args();

        return $this;
    }

    /**
     * @param array $args The expected arguments
     *
     * @return StubMethodWithArguments
     */
    public function withArgs(array $args)
    {
        $this->args = array_values($args);

        return $this;
    }

    /**
     * @return StubMethodWithArguments
     */
    public function withNoArgs()
    {
        return $this;
    }

    /**
     * @return StubMethodWithArguments
     */
    public function withAnyArgs()
    {
        $this->anyArgs = true;

        return $this;
    }

    /**
     * @void
     */
    public function toReturn($value)
    {
        $this->returnValue = $value;
    }

    /**
     * @TODO more complex comparisons here
     */
    public function matches($methodName, $args)
    {
        if ($this->name === $methodName && $this->args == $args) {
            return true;
        }

        if ($this->anyArgs && $this->name === $methodName) {
            return true;
        }

        return false;
    }

    public function getReturnValue()
    {
        return $this->returnValue;
    }
}

