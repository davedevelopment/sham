<?php

namespace Sham\TestDouble;

interface CallArgumentVerifier extends CallCountVerifier 
{
    /**
     * @param mixed $arg     The first expected argument
     * @param mixed $arg,... The subsequent expected arguments
     *
     * @return CallCountVerifier
     */
    function with($arg/*, $arg2..., $arg3...*/);

    /**
     * @param array $args The expected arguments
     *
     * @return CallCountVerifier
     */
    function withArgs(array $args);

    /**
     * @return CallCountVerifier
     */
    function withNoArgs();

    /**
     * @return CallCountVerifier
     */
    function withAnyArgs();
}
