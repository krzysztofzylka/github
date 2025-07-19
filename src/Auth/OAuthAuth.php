<?php

namespace krzysztofzylka\github\Auth;

/**
 * OAuth Token authentication
 */
class OAuthAuth implements AuthInterface
{
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getAuthHeader(): string
    {
        return 'Authorization: Bearer ' . $this->token;
    }
}