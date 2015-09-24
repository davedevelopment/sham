<?php

namespace Sham\TestDouble;

use Sham\TestDouble\Api\CallArgumentVerifier as CallArgumentVerifierApi;

class CallArgumentVerifier implements CallArgumentVerifierApi
{
    private $calls;

    public function __construct($calls)
    {
        $this->calls = $calls;
    }

    /**
     * @param mixed $arg     The first expected argument
     * @param mixed $arg,... The subsequent expected arguments
     *
     * @return CallCountVerifier
     */
    function with($arg/*, $arg2..., $arg3...*/)
    {
        return $this->withArgs(func_get_args());
    }

    /**
     * @param array $args The expected arguments
     *
     * @return CallCountVerifier
     */
    function withArgs(array $args)
    {
        $matches = $this->callsThatMatch($args);

        if (!empty($matches)) {
            return new static($matches);
        }

        /**
         * TODO Push message formatting up the stack
         */
        $methodName = current($this->calls)[0];

        $callFormatter = function ($call) {
            $string = "    {$call[0]}(\n";
            $argStrings = [];
            foreach ($call[1] as $arg) {
                if (is_object($arg) && $arg instanceof \Hamcrest\Core\IsTypeOf) {
                    $argStrings[] = "<".(string) $arg.">";
                } else if ($arg instanceof \PHPUnit_Framework_Constraint_IsType) {
                    $argStrings[] = "<".$arg->toString().">";
                } else {
                    $argStrings[] = gettype($arg)."($arg)";
                }
            }

            $string.= "        ".implode(",\n        ", $argStrings)."\n    )\n";

            return $string;
        };

        $message = "Couldn't verify this call was made\n\n";
        $message.= $callFormatter([$methodName, $args]);
        $message.= "\nThis method was called with:\n\n";
        foreach ($this->calls as $call) {
            $message.= $callFormatter($call)."\n";
        }

        throw new \Exception($message);
    }

    /**
     * @return CallCountVerifier
     */
    function withNoArgs()
    {
        return $this->withArgs([]);
    }

    /**
     * @return CallCountVerifier
     */
    function withAnyArgs()
    {
        return $this;
    }

    /**
     * @return void
     */
    public function once()
    {
        return $this->times(1);
    }

    /**
     * @return void
     */
    public function twice()
    {
        return $this->times(2);
    }

    /**
     * @param int $count
     *
     * @return void
     */
    public function times($count)
    {
        if ($count < 1) {
            throw new \Exception("Cannot verify a call was not received");
        }

        if (count($this->calls) !== $count) {
            throw new \Exception();
        }
    }

    private function callsThatMatch(array $args)
    {
        return array_filter($this->calls, function ($call) use ($args) {
            return $this->isMatch($call, $args);
        });
    }

    private function isMatch(array $call, array $expectedArgs)
    {
        $actual = $call[1];

        if (count($actual) !== count($expectedArgs)) {
            return false;
        }

        foreach ($expectedArgs as $i => $expectedArg) {

            if ($expectedArg instanceof \PHPUnit_Framework_Constraint) {
                if (!(new PHPUnitMatcher($expectedArg))->matches($actual[$i])) {
                    return false;
                }
            } else if ($expectedArg instanceof \Hamcrest\Matcher) {
                if (!(new HamcrestMatcher($expectedArg))->matches($actual[$i])) {
                    return false;
                }
            } else if ($expectedArg !== $actual[$i]) {
                return false;
            }
        }

        return true;
    }
}
