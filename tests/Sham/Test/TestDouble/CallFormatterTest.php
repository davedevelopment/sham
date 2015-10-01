<?php

namespace Sham\Test\TestDouble;

use Mockery as m;
use Sham\TestDouble\CallFormatter;

class CallFormatterTest extends \PHPUnit_Framework_TestCase
{

    /** 
     * @test 
     * @dataProvider calls
     */
    public function smoke_test($call, $expectedString)
    {
        $this->assertEquals($expectedString, CallFormatter::format($call));
    }

    public function calls()
    {
        return [
            "it deals with primitive args" => [
                ["methodName", ["string1", 2, 2.1, true, false, null]],
                "methodName(\n".
                "    string(string1),\n".
                "    integer(2),\n".
                "    double(2.1),\n".
                "    boolean(true),\n".
                "    boolean(false),\n".
                "    null\n".
                ")\n",
            ],

            "it deals with hamcrest args" => [
                ["methodName", [stringValue()]],
                "methodName(\n".
                "    <a string>\n".
                ")\n",
            ],

            "it deals with phpunit args" => [
                ["methodName", [new \PHPUnit_Framework_Constraint_IsType("string")]],
                "methodName(\n".
                "    <is of type \"string\">\n".
                ")\n",
            ],
        ];
    }
}
