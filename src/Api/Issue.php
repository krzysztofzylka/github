<?php

namespace krzysztofzylka\github\Api;

/**
 * Issue API
 */
class Issue extends AbstractApi
{

    /**
     * List issues
     * @param string $owner
     * @param string $repo
     * @param array $params
     * @return array
     */
    public function all(string $owner, string $repo, array $params = []): array
    {
        return $this->client->paginate("/repos/{$owner}/{$repo}/issues", $params);
    }

    /**
     * Get issue
     * @param string $owner
     * @param string $repo
     * @param string $number
     * @return array
     */
    public function get(string $owner, string $repo, string $number): array
    {
        return $this->client->get("/repos/{$owner}/{$repo}/issues/{$number}");
    }

    /**
     * Create issue
     * @param string $owner
     * @param string $repo
     * @param array $data
     * @return array
     */
    public function create(string $owner, string $repo, array $data): array
    {
        return $this->client->post("/repos/{$owner}/{$repo}/issues", $data);
    }

    /**
     * Update issue
     * @param string $owner
     * @param string $repo
     * @param string $number
     * @param array $data
     * @return array
     */
    public function update(string $owner, string $repo, string $number, array $data): array
    {
        return $this->client->patch("/repos/{$owner}/{$repo}/issues/{$number}", $data);
    }

    /**
     * Close issue
     * @param string $owner
     * @param string $repo
     * @param string $number
     * @return array
     */
    public function close(string $owner, string $repo, string $number): array
    {
        return $this->update($owner, $repo, $number, ['state' => 'closed']);
    }

    /**
     * Open issue
     * @param string $owner
     * @param string $repo
     * @param string $number
     * @return array
     */
    public function open(string $owner, string $repo, string $number): array
    {
        return $this->update($owner, $repo, $number, ['state' => 'open']);
    }

}