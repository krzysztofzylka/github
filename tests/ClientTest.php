<?php

namespace krzysztofzylka\github\Tests;

use PHPUnit\Framework\TestCase;
use krzysztofzylka\github\Client;
use krzysztofzylka\github\Auth\TokenAuth;

class ClientTest extends TestCase
{
    public function testClientCreation()
    {
        $auth = new TokenAuth('test-token');
        $client = new Client($auth);

        $this->assertInstanceOf(Client::class, $client);
    }

    public function testClientWithoutAuth()
    {
        $client = new Client();

        $this->assertInstanceOf(Client::class, $client);
    }

    public function testSetAuth()
    {
        $client = new Client();
        $auth = new TokenAuth('test-token');

        $result = $client->setAuth($auth);

        $this->assertInstanceOf(Client::class, $result);
    }

    public function testSetDebug()
    {
        $client = new Client();

        $result = $client->setDebug(true);

        $this->assertInstanceOf(Client::class, $result);
    }
}