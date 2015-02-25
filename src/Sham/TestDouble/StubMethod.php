<?php

namespace Sham\TestDouble;

interface StubMethod extends StubMethodWithArguments
{
    /**
     * @param mixed $arg     The first expected argument
     * @param mixed $arg,... The subsequent expected arguments
     *
     * @return StubMethodWithArguments
     */
    function with($arg/*, $arg2..., $arg3...*/);

    /**
     * @param array $args The expected arguments
     *
     * @return StubMethodWithArguments
     */
    function withArgs(array $args);

    /**
     * @return StubMethodWithArguments
     */
    function withNoArgs();

    /**
     * @return StubMethodWithArguments
     */
    function withAnyArgs();
}

