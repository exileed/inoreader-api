<?php

declare(strict_types=1);

namespace ExileeD\Inoreader\Test\HttpClient;

use ExileeD\Inoreader\Exception\InoreaderException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use ExileeD\Inoreader\HttpClient\HttpClient;
use ExileeD\Inoreader\HttpClient\GuzzleHttpClient;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\HandlerStack;

class GuzzleClientTest extends TestCase
{

    /**
     * @var GuzzleHttpClient
     */
    private $client;

    public function setUp(): void
    {
        $mock = new MockHandler(
            [
                new Response(200, [], 'Ok'),
            ]
        );

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $this->client = new GuzzleHttpClient($client);
    }

    public function testItChecksInstanceOfClient(): void
    {
        self::assertInstanceOf(Client::class, $this->client->getClient());
    }

    public function testItChecksCreateGuzzleClient(): void
    {
        $client = new GuzzleHttpClient();
        $client->setClient(new Client());
        self::assertInstanceOf(Client::class, $client->getClient());
    }

    public function testRequestResponceOk(): void
    {
        $response = $this->client->request('test');
        self::assertInstanceOf(ResponseInterface::class, $response);
        self::assertSame(200, $response->getStatusCode());
        self::assertSame('Ok', $response->getBody()->getContents());
    }

    public function testRequestResponseException(): void
    {
        $this->expectException(InoreaderException::class);

        $mock = new MockHandler(
            [
                new RequestException('Error Communicating with Server', new Request('GET', 'error')),
            ]
        );

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $client = new GuzzleHttpClient($client);

        $client->request('error');
    }
}
