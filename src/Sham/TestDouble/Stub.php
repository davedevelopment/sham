<?php

namespace Sham\TestDouble;

/**
 */
interface Stub extends TestDouble
{
    /**
     * @param  string   $method   The method to stub
     * @return StubMethod
     */
    function stub($method);

    /**
     * @return void
     */
    function shouldIgnoreMissing();
}
