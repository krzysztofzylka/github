<?php

namespace krzysztofzylka\github\Api;

use Exception;

/**
 * Authorization and permissions API
 */
class Authorization extends AbstractApi
{

    /**
     * Get token permissions information
     * @return array
     */
    public function getScopes(): array
    {
        return $this->client->getTokenScopes();
    }

    /**
     * Check if token has specific permission
     * @param string $scope
     * @return bool
     */
    public function hasScope(string $scope): bool
    {
        return $this->client->hasScope($scope);
    }

    /**
     * Check if token has all specified permissions
     * @param array $scopes
     * @return bool
     */
    public function hasAllScopes(array $scopes): bool
    {
        $tokenScopes = $this->getScopes();

        return count(array_intersect($scopes, $tokenScopes)) === count($scopes);
    }

    /**
     * Check if token has any of the specified permissions
     * @param array $scopes
     * @return bool
     */
    public function hasAnyScope(array $scopes): bool
    {
        $tokenScopes = $this->getScopes();

        return count(array_intersect($scopes, $tokenScopes)) > 0;
    }

    /**
     * Get list of missing permissions
     * @param array $requiredScopes
     * @return array
     */
    public function getMissingScopes(array $requiredScopes): array
    {
        $tokenScopes = $this->getScopes();

        return array_diff($requiredScopes, $tokenScopes);
    }

    /**
     * Check if specific action can be performed
     * @param string $action
     * @return bool
     */
    public function canPerformAction(string $action): bool
    {
        $requiredScopes = $this->getRequiredScopesForAction($action);

        return $this->hasAllScopes($requiredScopes);
    }

    /**
     * Map action to required permissions
     * @param string $action
     * @return array
     */
    private function getRequiredScopesForAction(string $action): array
    {
        $scopeMap = [
            'create_repo' => ['repo'],
            'delete_repo' => ['delete_repo'],
            'create_gist' => ['gist'],
            'manage_org' => ['admin:org'],
            'manage_webhooks' => ['admin:repo_hook', 'admin:org_hook'],
            'manage_deployments' => ['repo_deployment'],
            'manage_gpg_keys' => ['admin:gpg_key'],
            'manage_ssh_keys' => ['admin:public_key'],
            'access_notifications' => ['notifications'],
            'manage_workflow' => ['workflow'],
            'manage_packages' => ['write:packages', 'read:packages'],
            'access_user_email' => ['user:email'],
            'follow_users' => ['user:follow'],
        ];

        return $scopeMap[$action] ?? [];
    }

    /**
     * Get API rate limit information
     * @return array
     */
    public function getRateLimit(): array
    {
        return $this->client->get('/rate_limit');
    }

    /**
     * Check if token is valid
     * @return bool
     */
    public function isTokenValid(): bool
    {
        try {
            $this->client->get('/user');

            return true;
        } catch (Exception) {
            return false;
        }
    }

    /**
     * Get OAuth app information (if using OAuth App)
     * @param string $clientId
     * @param string $token
     * @return array
     */
    public function getOAuthAppInfo(string $clientId, string $token): array
    {
        return $this->client->get("/applications/{$clientId}/token", [
            'access_token' => $token
        ]);
    }

}