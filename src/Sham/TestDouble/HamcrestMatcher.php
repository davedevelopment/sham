<?php

namespace Sham\TestDouble;

class HamcrestMatcher implements Matcher
{
    private $expected;

    public function __construct(\Hamcrest\Matcher $expected)
    {
        $this->expected = $expected;
    }
    
    public function matches($actual)
    {
        return $this->expected->matches($actual);
    }
}
