<?php

namespace Sham\TestDouble;

interface MethodCallWithCallCountExpectation 
{
    /**
     * @param mixed $returnValue
     *
     * @return void
     */
    function andReturn($returnValue);
}
