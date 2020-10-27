<?php

declare(strict_types=1);

namespace ExileeD\Inoreader\Test;

use ExileeD\Inoreader\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{

    /**
     * @var Client
     */
    private $client;

    public function setUp(): void
    {
        $this->client = new Client();
    }

    public function testItChecksGetAccessToken(): void
    {
        $token = '123';
        $this->client->setAccessToken($token);
        self::assertSame($token, $this->client->getAccessToken());
    }

    public function testItChecksGetAccessTokenIsNull(): void
    {
        $this->client->setAccessToken();
        self::assertNull($this->client->getAccessToken());
    }
}
