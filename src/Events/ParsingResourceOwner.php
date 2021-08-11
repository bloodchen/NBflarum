<?php

namespace Chen\NbdomainLogin\Events;

class ParsingResourceOwner
{
    /**
     * @var array
     */
    public $response;

    public function __construct(array &$response)
    {
        $this->response = &$response;
    }
}
