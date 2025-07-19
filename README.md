# GitHub API Client Library

A PHP library for interacting with the GitHub API v3. This library provides a simple and intuitive interface for working with GitHub repositories, users, issues, pull requests, and more.

## Features

- **Authentication**: Support for Personal Access Tokens, OAuth tokens, and Basic authentication
- **Repository Management**: Create, update, delete repositories and manage branches
- **Issue Management**: Create, update, close, and manage issues
- **Pull Request Management**: Create, update, merge pull requests
- **User Management**: Get user information and manage user data
- **Organization Management**: Work with GitHub organizations
- **Authorization**: Check permissions and token validity
- **Rate Limiting**: Built-in rate limit handling
- **Pagination**: Automatic pagination for large result sets

## Installation

```bash
composer require krzysztozylka/github
```

## Quick Start

### Basic Usage

```php
use krzysztofzylka\github\Github;
use krzysztofzylka\github\Auth\TokenAuth;

// Create authentication
$auth = new TokenAuth('your-personal-access-token');

// Initialize GitHub client
$github = new Github($auth);

// Get authenticated user
$user = $github->users()->me();

// Get repository information
$repo = $github->repositories()->get('owner', 'repository-name');

// List issues
$issues = $github->issues()->all('owner', 'repository-name');
```

### Authentication Methods

#### Personal Access Token
```php
use krzysztofzylka\github\Auth\TokenAuth;

$auth = new TokenAuth('your-personal-access-token');
```

#### OAuth Token
```php
use krzysztofzylka\github\Auth\OAuthAuth;

$auth = new OAuthAuth('your-oauth-token');
```

#### Basic Authentication (Deprecated)
```php
use krzysztofzylka\github\Auth\BasicAuth;

$auth = new BasicAuth('username', 'password');
```

## API Examples

### Repository Operations

```php
// Get repository
$repo = $github->repositories()->get('owner', 'repo-name');

// Create repository
$newRepo = $github->repositories()->create([
    'name' => 'new-repository',
    'description' => 'Repository description',
    'private' => false
]);

// List user repositories
$repos = $github->repositories()->listUserRepos('username');

// List branches
$branches = $github->repositories()->listBranches('owner', 'repo-name');

// Create branch
$branch = $github->repositories()->createBranch('owner', 'repo-name', 'new-branch', 'commit-sha');
```

### Issue Operations

```php
// List issues
$issues = $github->issues()->all('owner', 'repo-name', [
    'state' => 'open',
    'labels' => 'bug'
]);

// Create issue
$issue = $github->issues()->create('owner', 'repo-name', [
    'title' => 'Bug report',
    'body' => 'Issue description',
    'labels' => ['bug', 'help wanted']
]);

// Update issue
$updatedIssue = $github->issues()->update('owner', 'repo-name', 123, [
    'title' => 'Updated title'
]);

// Close issue
$github->issues()->close('owner', 'repo-name', 123);
```

### Pull Request Operations

```php
// List pull requests
$prs = $github->pullRequests()->all('owner', 'repo-name', [
    'state' => 'open'
]);

// Create pull request
$pr = $github->pullRequests()->create('owner', 'repo-name', [
    'title' => 'Feature implementation',
    'head' => 'feature-branch',
    'base' => 'main',
    'body' => 'Pull request description'
]);

// Merge pull request
$github->pullRequests()->merge('owner', 'repo-name', 123, [
    'merge_method' => 'squash'
]);
```

### User Operations

```php
// Get authenticated user
$user = $github->users()->me();

// Get user by username
$user = $github->users()->get('username');

// Update user profile
$updatedUser = $github->users()->update([
    'name' => 'New Name',
    'bio' => 'Updated bio'
]);

// List user organizations
$orgs = $github->users()->organizations('username');
```

### Authorization and Permissions

```php
// Check token scopes
$scopes = $github->authorization()->getScopes();

// Check if token has specific scope
if ($github->authorization()->hasScope('repo')) {
    // Can access private repositories
}

// Check if token can perform action
if ($github->authorization()->canPerformAction('create_repo')) {
    // Can create repositories
}

// Get rate limit information
$rateLimit = $github->authorization()->getRateLimit();

// Check if token is valid
if ($github->authorization()->isTokenValid()) {
    // Token is working
}
```

## Error Handling

The library throws standard PHP exceptions for API errors:

```php
try {
    $repo = $github->repositories()->get('owner', 'non-existent-repo');
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    echo "Code: " . $e->getCode();
}
```

## Rate Limiting

The library automatically handles GitHub's rate limiting:

```php
// Get current rate limit information
$rateLimit = $github->getClient()->getRateLimit();

echo "Limit: " . $rateLimit['limit'];
echo "Remaining: " . $rateLimit['remaining'];
echo "Reset time: " . date('Y-m-d H:i:s', $rateLimit['reset']);
```

## Pagination

For endpoints that return large result sets, the library automatically handles pagination:

```php
// Get all repositories (automatically paginated)
$allRepos = $github->repositories()->listUserRepos('username');

// Limit pagination to specific number of pages
$limitedRepos = $github->getClient()->paginate('/user/repos', [], 5);
```

## Requirements

- PHP 8.0 or higher
- cURL extension
- JSON extension

## License

MIT License

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.