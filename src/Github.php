<?php

namespace krzysztofzylka\github;

use krzysztofzylka\github\Api\Authorization;
use krzysztofzylka\github\Api\Issue;
use krzysztofzylka\github\Api\Organization;
use krzysztofzylka\github\Api\PullRequest;
use krzysztofzylka\github\Api\Repository;
use krzysztofzylka\github\Api\User;
use krzysztofzylka\github\Auth\AuthInterface;

/**
 * Main facade class for easier usage
 */
class Github
{
    private Client $client;

    private Repository $repositories;

    private PullRequest $pullRequests;

    private Issue $issues;

    private User $users;

    private Organization $organizations;

    private Authorization $authorization;

    public function __construct(AuthInterface $auth = null)
    {
        $this->client = new Client($auth);
    }

    /**
     * Set authentication
     * @param AuthInterface $auth
     * @return Github
     */
    public function authenticate(AuthInterface $auth): self
    {
        $this->client->setAuth($auth);

        return $this;
    }

    /**
     * Get client access
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Repository API
     * @return Repository
     */
    public function repositories(): Repository
    {
        if (!isset($this->repositories)) {
            $this->repositories = new Repository($this->client);
        }

        return $this->repositories;
    }

    /**
     * Pull Request API
     * @return PullRequest
     */
    public function pullRequests(): PullRequest
    {
        if (!isset($this->pullRequests)) {
            $this->pullRequests = new PullRequest($this->client);
        }

        return $this->pullRequests;
    }

    /**
     * Issue API
     * @return Issue
     */
    public function issues(): Issue
    {
        if (!isset($this->issues)) {
            $this->issues = new Issue($this->client);
        }

        return $this->issues;
    }

    /**
     * User API
     * @return User
     */
    public function users(): User
    {
        if (!isset($this->users)) {
            $this->users = new User($this->client);
        }

        return $this->users;
    }

    /**
     * Organization API
     * @return Organization
     */
    public function organizations(): Organization
    {
        if (!isset($this->organizations)) {
            $this->organizations = new Organization($this->client);
        }

        return $this->organizations;
    }

    /**
     * Authorization API
     * @return Authorization
     */
    public function authorization(): Authorization
    {
        if (!isset($this->authorization)) {
            $this->authorization = new Authorization($this->client);
        }

        return $this->authorization;
    }
}