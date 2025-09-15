<?php

namespace krzysztofzylka\github\Auth;

/**
 * Basic authentication (username + password) - deprecated
 */
class BasicAuth implements AuthInterface
{

    private string $username;

    private string $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function getAuthHeader(): string
    {
        return 'Authorization: Basic ' . base64_encode($this->username . ':' . $this->password);
    }

}