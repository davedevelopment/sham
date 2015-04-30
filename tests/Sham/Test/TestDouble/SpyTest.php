<?php

namespace Sham\Test\TestDouble;

use Sham\TestDouble\Spy;

class SpyTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function it_should_ignore_all_calls()
    {
        $spy = new Spy();

        $spy->foo();
        $spy->bar(123);
    }

    /** @test */
    public function it_should_throw_if_it_has_not_received_method()
    {
        $spy = new Spy();
        $spy->bar();
        $this->setExpectedException('Exception');
        $spy->shouldHaveReceived('foo');
    }

    /** @test */
    public function it_can_verify_method_was_received()
    {
        $spy = new Spy();
        $spy->foo();
        $spy->shouldHaveReceived('foo');
    }

    /** @test */
    public function it_can_verify_a_method_call_was_receive_with_specific_arguments()
    {
        $spy = new Spy();

        $spy->baz('foo');
        $spy->foo('baz');
        $spy->foo('bar');

        $spy->shouldHaveReceived('foo')->with('bar');
    }

    /** @test */
    public function it_throws_if_it_has_not_received_a_method_call_with_specific_arguments()
    {
        $spy = new Spy();

        $spy->baz('foo');
        $spy->foo('bar');
        $spy->foo('baz', 'bar');

        $this->setExpectedException('Exception');
        $spy->shouldHaveReceived('foo')->with('baz');
    }

    /** @test */
    public function it_throws_when_it_has_received_a_call_it_should_not_have()
    {
        $spy = new Spy();

        $spy->foo();

        $this->setExpectedException('Exception');
        $spy->shouldNotHaveReceived("foo");
    }
}
