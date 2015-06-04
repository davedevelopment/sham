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
        foreach ($this->calls as $call) {
            if ($args === $call[1]) {
                return $this;
            }
        }

        throw new \Exception();
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
