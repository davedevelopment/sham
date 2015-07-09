<?php

namespace Sham\TestDouble;

interface Matcher 
{
    /**
     * @return bool
     */
    public function matches($actual);
}
