<?php

namespace krzysztofzylka\github\Api;

/**
 * Pull Request API
 */
class PullRequest extends AbstractApi
{

    /**
     * List pull requests
     * @param string $owner
     * @param string $repo
     * @param array $params
     * @return array
     */
    public function all(string $owner, string $repo, array $params = []): array
    {
        return $this->client->paginate("/repos/{$owner}/{$repo}/pulls", $params);
    }

    /**
     * Get pull request
     * @param string $owner
     * @param string $repo
     * @param string $number
     * @return array
     */
    public function get(string $owner, string $repo, string $number): array
    {
        return $this->client->get("/repos/{$owner}/{$repo}/pulls/{$number}");
    }

    /**
     * Create pull request
     * @param string $owner
     * @param string $repo
     * @param array $data
     * @return array
     */
    public function create(string $owner, string $repo, array $data): array
    {
        return $this->client->post("/repos/{$owner}/{$repo}/pulls", $data);
    }

    /**
     * Update pull request
     * @param string $owner
     * @param string $repo
     * @param string $number
     * @param array $data
     * @return array
     */
    public function update(string $owner, string $repo, string $number, array $data)
    {
        return $this->client->patch("/repos/{$owner}/{$repo}/pulls/{$number}", $data);
    }

    /**
     * Merge pull request
     * @param string $owner
     * @param string $repo
     * @param string $number
     * @param array $data
     * @return array
     */
    public function merge(string $owner, string $repo, string $number, array $data = []): array
    {
        return $this->client->put("/repos/{$owner}/{$repo}/pulls/{$number}/merge", $data);
    }

    /**
     * List files in PR
     * @param string $owner
     * @param string $repo
     * @param string $number
     * @return array
     */
    public function files(string $owner, string $repo, string $number): array
    {
        return $this->client->paginate("/repos/{$owner}/{$repo}/pulls/{$number}/files");
    }

    /**
     * List commits in PR
     * @param string $owner
     * @param string $repo
     * @param string $number
     * @return array
     */
    public function commits(string $owner, string $repo, string $number): array
    {
        return $this->client->paginate("/repos/{$owner}/{$repo}/pulls/{$number}/commits");
    }

}