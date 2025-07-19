<?php

namespace krzysztofzylka\github\Tests;

use PHPUnit\Framework\TestCase;
use krzysztofzylka\github\Github;
use krzysztofzylka\github\Auth\TokenAuth;

class GithubTest extends TestCase
{
    public function testGithubCreation()
    {
        $auth = new TokenAuth('test-token');
        $github = new Github($auth);

        $this->assertInstanceOf(Github::class, $github);
    }

    public function testGithubWithoutAuth()
    {
        $github = new Github();

        $this->assertInstanceOf(Github::class, $github);
    }

    public function testAuthenticate()
    {
        $github = new Github();
        $auth = new TokenAuth('test-token');

        $result = $github->authenticate($auth);

        $this->assertInstanceOf(Github::class, $result);
    }

    public function testGetClient()
    {
        $github = new Github();

        $client = $github->getClient();

        $this->assertInstanceOf(\krzysztofzylka\github\Client::class, $client);
    }
}