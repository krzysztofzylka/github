<?php

namespace krzysztofzylka\github\Api;

use krzysztofzylka\github\Client;

/**
 * Abstract base class for API resources
 */
abstract class AbstractApi
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
