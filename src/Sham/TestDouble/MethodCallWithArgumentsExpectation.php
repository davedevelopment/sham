<?php

namespace Sham\TestDouble;

interface MethodCallWithArgumentsExpectation extends MethodCallWithCallCountExpectation
{
    /**
     * @return MethodCallWithCallCountExpectation
     */
    function once();

    /**
     * @return MethodCallWithCallCountExpectation
     */
    function twice();

    /**
     * @param int $count 
     *
     * @return MethodCallWithCallCountExpectation
     */
    function times($count);
}
