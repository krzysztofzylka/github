<?php

namespace krzysztofzylka\github\Api;

/**
 * User API
 */
class User extends AbstractApi
{
    /**
     * Get authenticated user
     * @return array
     */
    public function me(): array
    {
        return $this->client->get("/user");
    }

    /**
     * Get user by username
     * @param string $username
     * @return array
     */
    public function get(string $username): array
    {
        return $this->client->get("/users/{$username}");
    }

    /**
     * Update authenticated user profile
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        return $this->client->patch("/user", $data);
    }

    /**
     * List emails
     * @return array
     */
    public function emails(): array
    {
        return $this->client->get("/user/emails");
    }

    /**
     * List user organizations
     * @param string|null $username
     * @return array
     */
    public function organizations(?string $username = null): array
    {
        $endpoint = $username ? "/users/{$username}/orgs" : "/user/orgs";

        return $this->client->paginate($endpoint);
    }
}