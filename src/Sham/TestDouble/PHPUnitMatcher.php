<?php

namespace Sham\TestDouble;

class PHPUnitMatcher implements Matcher
{
    private $expected;

    public function __construct(\PHPUnit_Framework_Constraint $expected)
    {
        $this->expected = $expected;
    }
    
    public function matches($actual)
    {
        return $this->expected->evaluate($actual, null, true);
    }
}
