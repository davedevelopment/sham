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

    private function isMatch(array $call, array $args)
    {
        if ($args === $call[1]) {
            return true;
        }

        return false;
    }
}
