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
        foreach ($this->calls as $call) {
            if (func_get_args() == $call[1]) {
                return $this;
            }
        }

        throw new \Exception();
    }

    /**
     * @param array $args The expected arguments
     *
     * @return CallCountVerifier
     */
    function withArgs(array $args) {}

    /**
     * @return CallCountVerifier
     */
    function withNoArgs() {}

    /**
     * @return CallCountVerifier
     */
    function withAnyArgs() {}

    /**
     * @return void
     */
    function once() {}

    /**
     * @return void
     */
    function twice() {}

    /**
     * @param int $count
     *
     * @return void
     */
    function times($count) {}
}
