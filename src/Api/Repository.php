<?php

namespace krzysztofzylka\github\Api;

/**
 * Repository API
 */
class Repository extends AbstractApi
{
    /**
     * Get repository information
     * @param string $owner
     * @param string $repo
     * @return array
     */
    public function get(string $owner, string $repo): array
    {
        return $this->client->get("/repos/{$owner}/{$repo}");
    }

    /**
     * List user repositories
     * @param string|null $username
     * @return array
     */
    public function listUserRepos(string $username = null): array
    {
        $endpoint = $username ? "/users/{$username}/repos" : "/user/repos";

        return $this->client->paginate($endpoint);
    }

    /**
     * Create new repository
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        return $this->client->post("/user/repos", $data);
    }

    /**
     * Update repository
     * @param string $owner
     * @param string $repo
     * @param array $data
     * @return array
     */
    public function update(string $owner, string $repo, array $data): array
    {
        return $this->client->patch("/repos/{$owner}/{$repo}", $data);
    }

    /**
     * Delete repository
     * @param string $owner
     * @param string $repo
     * @return array
     */
    public function delete(string $owner, string $repo): array
    {
        return $this->client->delete("/repos/{$owner}/{$repo}");
    }

    /**
     * Get branch
     * @param string $owner
     * @param string $repo
     * @param string $branch
     * @return array
     */
    public function getBranch(string $owner, string $repo, string $branch): array
    {
        return $this->client->get("/repos/{$owner}/{$repo}/branches/{$branch}");
    }

    /**
     * List branches
     * @param string $owner
     * @param string $repo
     * @return array
     */
    public function listBranches(string $owner, string $repo): array
    {
        return $this->client->paginate("/repos/{$owner}/{$repo}/branches");
    }

    /**
     * Create branch
     * @param string $owner
     * @param string $repo
     * @param string $branchName
     * @param string $sha
     * @return array
     */
    public function createBranch(string $owner, string $repo, string $branchName, string $sha): array
    {
        return $this->client->post("/repos/{$owner}/{$repo}/git/refs", [
            'ref' => "refs/heads/{$branchName}",
            'sha' => $sha
        ]);
    }

    /**
     * Delete branch
     * @param string $owner
     * @param string $repo
     * @param string $branchName
     * @return array
     */
    public function deleteBranch(string $owner, string $repo, string $branchName): array
    {
        return $this->client->delete("/repos/{$owner}/{$repo}/git/refs/heads/{$branchName}");
    }

    /**
     * List commits
     * @param string $owner
     * @param string $repo
     * @param array $params
     * @return array
     */
    public function listCommits(string $owner, string $repo, array $params = []): array
    {
        return $this->client->paginate("/repos/{$owner}/{$repo}/commits", $params);
    }

    /**
     * Compare two commits/branches
     * @param string $owner
     * @param string $repo
     * @param string $base
     * @param string $head
     * @return array
     */
    public function compare(string $owner, string $repo, string $base, string $head): array
    {
        return $this->client->get("/repos/{$owner}/{$repo}/compare/{$base}...{$head}");
    }
}
