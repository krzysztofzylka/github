<?php

namespace krzysztofzylka\github\Auth;

/**
 * Personal Access Token authentication
 */
class TokenAuth implements AuthInterface
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