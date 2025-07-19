<?php

namespace krzysztofzylka\github\Tests;

use PHPUnit\Framework\TestCase;
use krzysztofzylka\github\Auth\TokenAuth;
use krzysztofzylka\github\Auth\OAuthAuth;
use krzysztofzylka\github\Auth\BasicAuth;

class AuthTest extends TestCase
{
    public function testTokenAuth()
    {
        $auth = new TokenAuth('test-token');

        $this->assertInstanceOf(TokenAuth::class, $auth);
        $this->assertEquals('Authorization: Bearer test-token', $auth->getAuthHeader());
    }

    public function testOAuthAuth()
    {
        $auth = new OAuthAuth('test-oauth-token');

        $this->assertInstanceOf(OAuthAuth::class, $auth);
        $this->assertEquals('Authorization: Bearer test-oauth-token', $auth->getAuthHeader());
    }

    public function testBasicAuth()
    {
        $auth = new BasicAuth('username', 'password');

        $this->assertInstanceOf(BasicAuth::class, $auth);
        $this->assertEquals('Authorization: Basic ' . base64_encode('username:password'), $auth->getAuthHeader());
    }
}