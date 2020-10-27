<?php

declare(strict_types=1);

namespace ExileeD\Inoreader;

use ExileeD\Inoreader\HttpClient\HttpClient;
use ExileeD\Inoreader\HttpClient\GuzzleHttpClient;
use ExileeD\Inoreader\Exception\InoreaderException;
use Psr\Http\Message\ResponseInterface;

class Client
{

    /**
     * The default base URL.
     *
     * @var string
     */
    private const BASE_URL = 'https://www.inoreader.com/reader/api/0/';


    /**
     * The default base OAuth2 URL.
     *
     * @var string
     */
    private const API_OAUTH = 'https://www.inoreader.com/oauth2/';

    /**
     * The default user agent header.
     *
     * @var string
     */
    private const USER_AGENT = 'inoreader-php/1.0.0 (+https://github.com/exileed/inoreader-api)';

    /**
     * @var array
     */
    private array $defaultHeaders = [
        'User-Agent' => self::USER_AGENT,
    ];

    /**
     * @var HttpClient
     */
    private $httpClient;


    /** @var string|null */
    private $accessToken = null;

    /**
     * Instantiates a new Client object.
     *
     * @param HttpClient|null $httpClient
     */
    public function __construct(HttpClient $httpClient = null)
    {
        $this->httpClient = $httpClient ?? new GuzzleHttpClient();
    }

    /**
     * @return string
     * @todo
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * @param string|null $token
     *
     * @return void
     */
    public function setAccessToken(string $token = null)
    {
        if ($token === null) {
            unset($this->defaultHeaders[ 'Authorization' ]);
            $this->accessToken = null;
        } else {
            $this->defaultHeaders[ 'Authorization' ] = \sprintf('Bearer %s', $token);
            $this->accessToken                       = $token;
        }
    }

    /**
     * Returns the inoreader client's http client to the given http client. Client.
     *
     * @return  HttpClient
     */
    public function getHttpClient(): HttpClient
    {
        return $this->httpClient;
    }

    /**
     * Makes a GET request to the Inoreader API and returns the response
     *
     * @param string $endpoint
     * @param array  $params
     *
     * @return \stdClass
     * @throws InoreaderException
     */
    public function get(string $endpoint, $params = [])
    {
        return $this->send('GET', $endpoint, $params);
    }

    /**
     * Makes a POST request to the Inoreader API and returns the response
     *
     * @param string       $endpoint
     * @param string|array $body
     *
     * @return \stdClass
     * @throws InoreaderException
     */
    public function post(string $endpoint, $body)
    {
        return $this->send('POST', $endpoint, $body);
    }

    /**
     * Makes a request to the Inoreader API and returns the response
     *
     * @param string $method
     * @param string $uri
     * @param        $body
     * @param array  $headers
     *
     * @return bool|\stdClass
     */
    private function send(string $method, string $uri, $body, array $headers = [])
    {
        $url = \sprintf('%s%s', self::BASE_URL, $uri);

        $headers = \array_merge($this->defaultHeaders, $headers);

        $response = $this->httpClient->request($url, $body, $method, $headers);

        return $this->processResponse($response);
    }

    /**
     *
     * @param ResponseInterface $response
     *
     * @return   \stdClass|bool The JSON response from the request
     * @throws   InoreaderException
     */
    private function processResponse(ResponseInterface $response)
    {
        $content = $response->getBody()->getContents();
        if ($content === 'OK') {
            return true;
        }

        return json_decode($content, false, 512, JSON_THROW_ON_ERROR);
    }
}
