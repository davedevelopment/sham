<?php

namespace Sham\TestDouble\Api;

interface CallCountVerifier 
{
    /**
     * @return void
     */
    function once();

    /**
     * @return void
     */
    function twice();

    /**
     * @param int $count
     *
     * @return void
     */
    function times($count);
}

