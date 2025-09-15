<?php

namespace krzysztofzylka\github\Api;

/**
 * Organization API
 */
class Organization extends AbstractApi
{

    /**
     * Get organization
     * @param string $org
     * @return array
     */
    public function get(string $org): array
    {
        return $this->client->get("/orgs/{$org}");
    }

    /**
     * List organization repositories
     * @param string $org
     * @return array
     */
    public function repositories(string $org): array
    {
        return $this->client->paginate("/orgs/{$org}/repos");
    }

    /**
     * List organization members
     * @param string $org
     * @return array
     */
    public function members(string $org): array
    {
        return $this->client->paginate("/orgs/{$org}/members");
    }

    /**
     * List teams in organization
     * @param string $org
     * @return array
     */
    public function teams(string $org): array
    {
        return $this->client->paginate("/orgs/{$org}/teams");
    }

}