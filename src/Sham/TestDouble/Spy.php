<?php

namespace Sham\TestDouble;

use Sham\TestDouble\Api\Spy as SpyApi;

class Spy extends Stub implements SpyApi
{
    private $methods = [];

    public function __construct()
    {
        parent::__construct();

        $this->shouldIgnoreMissing();
    }

    /**
     * @param   string  $method     The method that is expected to be have been called
     *
     * @return CallArgumentVerifier
     */
    function shouldHaveReceived($method)
    {
        $found = array();

        foreach ($this->methods as $call) {
            if ($call[0] == $method) {
                $found[] = $call;
            }
        }

        if (empty($found))
            throw new \Exception();

        return new CallArgumentVerifier($found);
    }

    /**
     * @param   string  $method     The method that is expected to not have been called
     * @param   array   $args       The arguments that were expected to not have been passed (optional)
     *
     * @return void
     */
    function shouldNotHaveReceived($method, array $args = array())
    {
    }

    public function __call($methodName, $args)
    {
        $this->methods[] = [$methodName, $args];
    }

}
