<?php

namespace Sham\TestDouble;

interface MethodCallExpectation extends MethodCallWithArgumentsExpectation
{
    /**
     * @param mixed $arg     The first expected argument
     * @param mixed $arg,... The subsequent expected arguments
     *
     * @return MethodCallWithArgumentsExpectation
     */
    function with($arg/*, $arg2..., $arg3...*/);

    /**
     * @param array $args The expected arguments
     *
     * @return MethodCallWithArgumentsExpectation
     */
    function withArgs(array $args);

    /**
     * @return MethodCallWithArgumentsExpectation
     */
    function withNoArgs();

    /**
     * @return MethodCallWithArgumentsExpectation
     */
    function withAnyArgs();
}
