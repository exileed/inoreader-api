<?php

declare(strict_types=1);

namespace ExileeD\Inoreader\HttpClient;

use ExileeD\Inoreader\Exception\InoreaderException;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class GuzzleHttpClient implements HttpClient
{
    /**
     * @var GuzzleClient
     */
    private $client;

    /**
     * @param ClientInterface|null $client
     *
     * @return void
     */
    public function __construct(ClientInterface $client = null)
    {
        $this->client = $client ?? self::createClient();
    }

    /**
     * @return ClientInterface
     */
    private static function createClient()
    {
        return new GuzzleClient();
    }

    /**
     * @inheritdoc
     */
    public function request($endpoint, $params = [], $method = 'GET', array $headers = []): ResponseInterface
    {
        $options = array_merge(
            $params,
            [
                'headers' => $headers,
            ]
        );
        try {
            return $this->getClient()->request($method, $endpoint, $options);
        } catch (GuzzleException $e) {
            throw new InoreaderException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return GuzzleClient
     */
    public function getClient(): GuzzleClient
    {
        return $this->client;
    }

    /**
     * @param GuzzleClient $client
     */
    public function setClient(GuzzleClient $client): void
    {
        $this->client = $client;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return array
     */
    private static function getHeaders(ResponseInterface $response)
    {
        return [
            'Content-Type' => $response->getHeader('Content-Type'),
            'X-Reader-Zone1-Limit' => $response->getHeader('X-Reader-Zone1-Limit'),
            'X-Reader-Zone2-Limit' => $response->getHeader('X-Reader-Zone2-Limit'),
            'X-Reader-Zone1-Usage' => $response->getHeader('X-Reader-Zone1-Usage'),
            'X-Reader-Zone2-Usage' => $response->getHeader('X-Reader-Zone2-Usage'),
            'X-Reader-Limits-Reset-After' => $response->getHeader('X-Reader-Limits-Reset-After'),
        ];
    }
}
