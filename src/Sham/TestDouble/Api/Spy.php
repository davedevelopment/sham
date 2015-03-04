<?php

namespace Sham\TestDouble\Api;

interface Spy extends Stub
{
    /**
     * @param   string  $method     The method that is expected to be have been called
     *
     * @return CallArgumentVerifier
     */
    function shouldHaveReceived($method);

    /**
     * @param   string  $method     The method that is expected to not have been called
     * @param   array   $args       The arguments that were expected to not have been passed (optional)
     *
     * @return void
     */
    function shouldNotHaveReceived($method, array $args = array());
}
