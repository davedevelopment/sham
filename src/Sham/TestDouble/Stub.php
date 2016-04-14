<?php

namespace Sham\TestDouble;

class Stub implements Api\Stub
{
    use StubTrait;
}

trait StubTrait
{
    private $shouldIgnoreMissing = false;
    private $stubMethods = [];

    /**
     * @param  string   $method   The method to stub
     * @return StubMethod
     */
    public function stub($method)
    {
        $stubMethod = new StubMethod($method);

        $this->stubMethods[] = $stubMethod;

        return $stubMethod;
    }

    /**
     * @return void
     */
    public function shouldIgnoreMissing()
    {
        $this->shouldIgnoreMissing = true;
    }

    public function __call($methodName, $args)
    {
        foreach (array_reverse($this->stubMethods) as $stubMethod) {
            if ($stubMethod->matches($methodName, $args)) {
                return $stubMethod->getReturnValue();
            }
        }

        if ($this->shouldIgnoreMissing) {
            return;
        }

        throw new \Exception("Method not found");
    }
}
