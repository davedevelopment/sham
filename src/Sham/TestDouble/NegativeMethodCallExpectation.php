<?php

namespace Sham\TestDouble;

interface NegativeMethodCallExpectation 
{
    /**
     * @param mixed $arg     The first expected argument
     * @param mixed $arg,... The subsequent expected arguments
     *
     * @return void
     */
    function with($arg/*, $arg2..., $arg3...*/);

    /**
     * @param array $args The expected arguments
     *
     * @return void
     */
    function withArgs(array $args);

    /**
     * @return void
     */
    function withNoArgs();

    /**
     * @return void
     */
    function withAnyArgs();
}
