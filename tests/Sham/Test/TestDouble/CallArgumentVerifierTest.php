<?php

namespace Sham\Test\TestDouble;

use Sham\TestDouble\CallArgumentVerifier;
use Sham\TestDouble\Api\CallCountVerifier;
use PHPUnit_Framework_Constraint_IsType;

class CallArgumentVerifierTest extends \PHPUnit_Framework_TestCase
{
    public function basic_with()
    {
        return [
            'basic test with one arg' => [
                [['dummy_method', ['123']]],
                ['123'],
                true,
            ],
            'basic test with single incorrect arg' => [
                [['dummy_method', ['123']]],
                ['dave'],
                false,
            ],
            'basic test with multiple arg' => [
                [['dummy_method', ['123', false, 456]]],
                ['123', false, 456],
                true,
            ],
            'basic test with multiple incorrect arg' => [
                [['dummy_method', ['123', false, 456]]],
                ['dave', false, 456],
                false,
            ],
            'basic test with multiple calls' => [
                [
                    ['dummy_method', ['456']],
                    ['dummy_method', ['123']]
                ],
                ['123'],
                true,
            ],
            'basic test with multiple calls and non-match' => [
                [
                    ['dummy_method', ['456']],
                    ['dummy_method', ['123']]
                ],
                ['789'],
                false,
            ],
            'with should not match on loose equivalence' => [
                [['dummy_method', [123]]],
                ['123'],
                false,
            ],
        ];
    }

    /** 
     * @test 
     * @dataProvider basic_with
     */
    public function test_with($receivedCalls, $argsToCheck, $shouldSucceed)
    {
        $verifier = new CallArgumentVerifier($receivedCalls);

        if (!$shouldSucceed) {
            $this->setExpectedException("Exception");
        }

        call_user_func_array([$verifier, 'with'], $argsToCheck);
    }      

    /** 
     * @test 
     * @dataProvider basic_with
     */
    public function test_with_args($receivedCalls, $argsToCheck, $shouldSucceed)
    {
        $verifier = new CallArgumentVerifier($receivedCalls);

        if (!$shouldSucceed) {
            $this->setExpectedException("Exception");
        }

        $verifier->withArgs($argsToCheck);
    }      

    /** @test */
    public function it_can_verify_no_args_where_passed()
    {
        $verifier = new CallArgumentVerifier([['dummy_method', []]]);

        $verifier->withNoArgs();
    }

    /** @test */
    public function it_can_verify_there_was_one_call_of_many_with_no_args()
    {
        $verifier = new CallArgumentVerifier([
            ['dummy_method', ['123']],
            ['dummy_method', []],
            ['dummy_method', ['456']],
        ]);

        $verifier->withNoArgs();
    }

    /** @test */
    public function it_throws_if_args_have_been_passed_when_they_shouldnt()
    {
        $verifier = new CallArgumentVerifier([['dummy_method', ['123']]]);

        $this->setExpectedException("Exception");

        $verifier->withNoArgs();
    }

    /** @test */
    public function it_provides_fluent_interface()
    {
        $verifier = new CallArgumentVerifier([
            ['dummy_method', ['123']],
            ['dummy_method', []],
        ]);

        $this->assertInstanceOf(CallCountVerifier::class, $verifier->with('123'));
        $this->assertInstanceOf(CallCountVerifier::class, $verifier->withArgs(['123']));
        $this->assertInstanceOf(CallCountVerifier::class, $verifier->withNoArgs());
        $this->assertInstanceOf(CallCountVerifier::class, $verifier->withAnyArgs());
    }

    /** @test */
    public function it_can_verify_the_number_of_matching_calls()
    {
        $verifier = new CallArgumentVerifier([
            ['dummy_method', ['123']],
        ]);

        $verifier->with('123')->once();
    }

    /** @test */
    public function it_throws_if_the_number_of_calls_is_more_than_specified()
    {
        $verifier = new CallArgumentVerifier([
            ['dummy_method', ['123']],
            ['dummy_method', ['123']],
        ]);

        $this->setExpectedException("Exception");

        $verifier->with('123')->once();
    }

    /** @test */
    public function it_throws_if_the_number_of_calls_is_less_than_specified()
    {
        $verifier = new CallArgumentVerifier([
            ['dummy_method', ['123']],
        ]);

        $this->setExpectedException("Exception");

        $verifier->with('123')->twice();
    }

    /** @test */
    public function it_can_verify_an_arbitrary_number_of_calls()
    {
        $verifier = new CallArgumentVerifier([
            ['dummy_method', ['123']],
            ['dummy_method', ['123']],
            ['dummy_method', ['123']],
            ['dummy_method', ['123']],
        ]);

        $verifier->with('123')->times(4);
    }

    /** @test */
    public function it_throws_if_someone_tries_to_verify_less_than_one_call()
    {
        $verifier = new CallArgumentVerifier([]);

        $this->setExpectedException("Exception");

        $verifier->times(0);
    }

    /** @test */
    public function it_can_verify_calls_based_on_phpunit_constraints()
    {
        $verifier = new CallArgumentVerifier([
            ['dummy_method', ['123']],
        ]);

        $verifier->with(new PHPUnit_Framework_Constraint_IsType("string"));
    }

    /** @test */
    public function it_throws_if_a_phpunit_constraint_does_not_match()
    {
        $verifier = new CallArgumentVerifier([
            ['dummy_method', [123]],
        ]);

        $this->setExpectedException("Exception");
        $verifier->with(new PHPUnit_Framework_Constraint_IsType("string"));
    }
    /** @test */
    public function it_throws_if_theres_not_a_match_even_with_a_phpunit_positive_match()
    {
        $verifier = new CallArgumentVerifier([
            ['dummy_method', [123]],
            ['dummy_method', [456]],
        ]);

        $expectedMessage = <<<EOS
Couldn't verify this call was made

    dummy_method(
        <is of type "string">
    )

This method was called with:

    dummy_method(
        integer(123)
    )

    dummy_method(
        integer(456)
    )
EOS;

        $this->setExpectedException("Exception", $expectedMessage);
        $verifier->with(new PHPUnit_Framework_Constraint_IsType("string"));
    }

    /** @test */
    public function it_can_verify_calls_based_on_hamcrest_constraints()
    {
        $verifier = new CallArgumentVerifier([
            ['dummy_method', ['123']],
        ]);

        $verifier->with(stringValue());
    }

    /** @test */
    public function it_throws_if_a_hamcreset_constraint_does_not_match()
    {
        $verifier = new CallArgumentVerifier([
            ['dummy_method', [123]],
            ['dummy_method', [456]],
        ]);

        $expectedMessage = <<<EOS
Couldn't verify this call was made

    dummy_method(
        <a string>
    )

This method was called with:

    dummy_method(
        integer(123)
    )

    dummy_method(
        integer(456)
    )
EOS;

        $this->setExpectedException("Exception", $expectedMessage);
        $verifier->with(stringValue());
    }

    /** @test */
    public function it_throws_if_theres_not_a_match_even_with_a_hamcreset_positive_match()
    {
        $verifier = new CallArgumentVerifier([
            ['dummy_method', ['123', 123]],
        ]);

        $this->setExpectedException("Exception");
        $verifier->with(stringValue("string"), 456);
    }
}
