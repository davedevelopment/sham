<?php

namespace Sham\TestDouble\Api;

interface Mock extends Stub
{
    /**
     * @param   string  $method     The method that is expected to be called
     *
     * @return MethodCallExpectation
     */
    function shouldReceive($method);

    /**
     * @param   string  $method     The method that is expected to be called
     *
     * @return NegativeMethodCallExpectation
     */
    function shouldNotReceive($method);
}
