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
    public function it_throws_if_if_has_not_received_identical_arguments_as_specified()
    {
        $spy = new Spy();

        $spy->foo(123);

        $this->setExpectedException('Exception');
        $spy->shouldHaveReceived('foo')->with('123');
    }

    /** @test */
    public function it_throws_when_it_has_received_a_call_it_should_not_have()
    {
        $spy = new Spy();

        $spy->foo();

        $this->setExpectedException('Exception');
        $spy->shouldNotHaveReceived("foo");
    }

    /** @test */
    public function it_verifies_a_call_with_specific_arguments_was_not_received()
    {
        $spy = new Spy();

        $spy->foo();
        $spy->foo('bar','bat');
        $spy->foo('1','4');

        $spy->shouldNotHaveReceived("foo", [1,2]);
    }

    /** @test */
    public function it_throws_when_it_has_received_a_call_with_args_when_it_should_not_have_received_call_at_all()
    {
        $spy = new Spy();

        $spy->foo('bar','bat');

        $this->setExpectedException('Exception');
        $spy->shouldNotHaveReceived("foo");
    }

    /** @test */
    public function it_verifies_when_it_has_received_a_call_with_with_args_when_it_should_not_have_received_call_with_no_args()
    {
        $spy = new Spy();

        $spy->foo(123);

        $spy->shouldNotHaveReceived("foo", []);
    }

    /** @test */
    public function it_throws_when_it_has_received_a_call_with_no_args_when_it_should_not_have_received_call_with_no_args()
    {
        $spy = new Spy();

        $spy->foo();

        $this->setExpectedException('Exception');
        $spy->shouldNotHaveReceived("foo", []);
    }

    /** @test */
    public function it_throws_when_it_has_received_a_call_with_args_it_should_not_have()
    {
        $spy = new Spy();

        $spy->foo('bar','baz');

        $this->setExpectedException('Exception');
        $spy->shouldNotHaveReceived("foo", ['bar','baz']);
    }
}
