<?php

namespace Sham\TestDouble;

class StubMethod implements Api\StubMethod
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

