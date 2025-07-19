<?php

namespace krzysztofzylka\github;

use krzysztofzylka\github\Auth\AuthInterface;
use Exception;

class Client
{
    /**
     * API URL
     * @var string
     */
    private string $apiUrl = 'https://api.github.com';

    /**
     * Authentication
     * @var ?AuthInterface
     */
    private ?AuthInterface $auth;

    /**
     * Last response
     * @var mixed
     */
    private mixed $lastResponse;

    /**
     * Rate limits
     * @var array
     */
    private array $rateLimit = [];

    /**
     * Debug option
     * @var bool
     */
    private bool $debug = false;

    /**
     * Token scopes
     * @var array
     */
    private array $tokenScopes = [];

    /**
     * Constructor
     * @param ?AuthInterface $auth Authentication object
     */
    public function __construct(?AuthInterface $auth = null)
    {
        $this->auth = $auth;
    }

    /**
     * Set authentication method
     * @param AuthInterface $auth
     * @return Client
     */
    public function setAuth(AuthInterface $auth): self
    {
        $this->auth = $auth;

        return $this;
    }

    /**
     * Enable/disable debug mode
     * @param bool $debug
     * @return Client
     */
    public function setDebug(bool $debug = true): self
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * Execute GET request
     * @param string $endpoint
     * @param array $params
     * @return array
     */
    public function get(string $endpoint, array $params = []): array
    {
        return $this->request('GET', $endpoint, $params);
    }

    /**
     * Execute POST request
     * @param string $endpoint
     * @param array $data
     * @return array
     */
    public function post(string $endpoint, array $data = []): array
    {
        return $this->request('POST', $endpoint, $data);
    }

    /**
     * Execute PUT request
     * @param $endpoint
     * @param array $data
     * @return array
     */
    public function put($endpoint, array $data = []): array
    {
        return $this->request('PUT', $endpoint, $data);
    }

    /**
     * Execute PATCH request
     * @param string $endpoint
     * @param array $data
     * @return array
     */
    public function patch(string $endpoint, array $data = []): array
    {
        return $this->request('PATCH', $endpoint, $data);
    }

    /**
     * Execute DELETE request
     * @param string $endpoint
     * @return mixed
     */
    public function delete(string $endpoint): mixed
    {
        return $this->request('DELETE', $endpoint);
    }

    /**
     * Main method for executing requests
     * @param string $method
     * @param string $endpoint
     * @param array $data
     * @return array
     */
    private function request(string $method, string $endpoint, array $data = []): array
    {
        $url = $this->apiUrl . $endpoint;

        if ($method === 'GET' && !empty($data)) {
            $url .= '?' . http_build_query($data);
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        $headers = [
            'Accept: application/vnd.github.v3+json',
            'User-Agent: PHP-GitHub-Client/1.0'
        ];

        if (isset($this->auth)) {
            $authHeader = $this->auth->getAuthHeader();

            if ($authHeader) {
                $headers[] = $authHeader;
            }
        }

        if (in_array($method, ['POST', 'PUT', 'PATCH']) && !empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $headers[] = 'Content-Type: application/json';
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($this->debug) {
            curl_setopt($ch, CURLOPT_VERBOSE, true);
        }

        $response = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $header = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);

        curl_close($ch);

        $this->parseHeaders($header);

        $this->lastResponse = [
            'code' => $httpCode,
            'headers' => $header,
            'body' => $body
        ];

        if ($httpCode >= 400) {
            $this->handleError($httpCode, $body);
        }

        if ($body === '') {
            $body = '[]';
        }

        return json_decode($body, true);
    }

    /**
     * Parse response headers
     * @param string $headerString
     */
    private function parseHeaders(string $headerString): void
    {
        $headers = [];
        $lines = explode("\n", $headerString);

        foreach ($lines as $line) {
            if (str_contains($line, ':')) {
                list($key, $value) = explode(':', $line, 2);
                $headers[trim($key)] = trim($value);
            }
        }

        if (isset($headers['x-ratelimit-limit'])) {
            $this->rateLimit = [
                'limit' => (int)$headers['x-ratelimit-limit'],
                'remaining' => (int)$headers['x-ratelimit-remaining'],
                'reset' => (int)$headers['x-ratelimit-reset'],
                'used' => (int)$headers['x-ratelimit-used'],
            ];
        }

        if (isset($headers['x-oauth-scopes'])) {
            $this->tokenScopes = array_filter(array_map('trim', explode(',', $headers['x-oauth-scopes'])));
        }
    }

    /**
     * Handle API errors
     * @throws Exception
     */
    private function handleError($code, $body): void
    {
        $error = json_decode($body, true);
        $message = $error['message'] ?? 'Unknown error';

        if (isset($error['errors'][0]['message'])) {
            $message = $error['errors'][0]['message'];
        }

        throw match ($code) {
            401 => new Exception($message, 401),
            403 => new Exception($message, 403),
            404 => new Exception($message, 404),
            422 => new Exception($message, 422),
            default => new Exception($message, $code),
        };
    }

    /**
     * Get rate limit information
     * @return array
     */
    public function getRateLimit(): array
    {
        return $this->rateLimit;
    }

    /**
     * Get token scopes
     * @return array
     */
    public function getTokenScopes(): array
    {
        if (empty($this->tokenScopes)) {
            $this->get('/user');
        }

        return $this->tokenScopes;
    }

    /**
     * Check if token has specific scope
     * @param $scope
     * @return bool
     */
    public function hasScope($scope): bool
    {
        $scopes = $this->getTokenScopes();

        return in_array($scope, $scopes);
    }

    /**
     * Get last response
     * @return mixed
     */
    public function getLastResponse(): mixed
    {
        return $this->lastResponse;
    }

    /**
     * Get all pages of results (pagination)
     * @param string $endpoint
     * @param array $params
     * @param null $maxPages
     * @return array
     */
    public function paginate(string $endpoint, array $params = [], $maxPages = null): array
    {
        $allResults = [];
        $page = 1;

        while (true) {
            $params['page'] = $page;
            $params['per_page'] = $params['per_page'] ?? 100;

            $results = $this->get($endpoint, $params);

            if (empty($results)) {
                break;
            }

            $allResults = array_merge($allResults, $results);

            if ($maxPages && $page >= $maxPages) {
                break;
            }

            if (count($results) < $params['per_page']) {
                break;
            }

            $page++;
        }

        return $allResults;
    }
}