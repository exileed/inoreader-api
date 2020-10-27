<?php

declare(strict_types=1);

namespace ExileeD\Inoreader\Test;

use ExileeD\Inoreader\HttpClient\GuzzleHttpClient;
use ExileeD\Inoreader\Inoreader;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class InoreaderTest extends TestCase
{
    /**
     * @var Inoreader
     */
    private $api;

    public function setUp(): void
    {
        $mock = new MockHandler(
            [
                new Response(200, [], 'OK'),
            ]
        );

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $httpClient = new GuzzleHttpClient($client);

        $this->api = new Inoreader(111, 'token', $httpClient);
    }

    public function testCheckDeleteActiveSearchResponseOk(): void
    {
        $response = $this->api->deleteActiveSearch('test');

        self::assertTrue($response);
    }
}
