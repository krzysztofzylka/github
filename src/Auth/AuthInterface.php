<?php

namespace krzysztofzylka\github\Auth;

/**
 * Interface for authentication methods
 */
interface AuthInterface
{
    /**
     * Get authorization headers
     * @return string
     */
    public function getAuthHeader(): string;
}