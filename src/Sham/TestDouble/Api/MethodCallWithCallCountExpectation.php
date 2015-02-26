<?php

namespace Sham\TestDouble\Api;

interface MethodCallWithCallCountExpectation 
{
    /**
     * @param mixed $returnValue
     *
     * @return void
     */
    function andReturn($returnValue);
}
