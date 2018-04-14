<?php declare(strict_types=1);

namespace ExileeD\Inoreader\Client;

use ExileeD\Inoreader\Exception\InoreaderException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class GuzzleClient implements ClientInterface
{

    /**
     * @var HttpClient
     */
    protected $client;
    /**
     * @var array
     */
    private $options = [
        'base_uri'    => 'https://www.inoreader.com/reader/api/0/',
        'user_agent'  => 'inoreader-php/1.0.0 (+https://github.com/exileed/inoreader-api)',
        'timeout'     => 10,
        'verify_peer' => true,
    ];

    public function __construct()
    {
        $this->client = new HttpClient([
            'base_uri' => $this->getApiBaseUrl(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getApiBaseUrl(): string
    {
        return $this->options[ 'base_uri' ];
    }

    public function get($endpoint, $params = [], $headers = []): ResponseInterface
    {
        $query = [
            'query' => $params,
        ];

        return $this->request($endpoint, $query, 'GET', $headers);
    }

    /**
     * @inheritdoc
     */
    public function request($endpoint, $params = [], $method = 'GET', array $headers = []): ResponseInterface
    {

        $options = array_merge($params, [
            'User-Agent' => $this->options[ 'user_agent' ],
            'headers'    => $headers,
        ]);

        try {
            return $this->getClient()->request($method, $endpoint, $options);
        } catch (GuzzleException $e) {
            throw new InoreaderException($e->getMessage());
        }
    }

    /**
     * @access public
     * @return HttpClient
     */
    public function getClient(): HttpClient
    {
        return $this->client;
    }

    /**
     * @param HttpClient $client
     */
    public function setClient(HttpClient $client): void
    {
        $this->client = $client;
    }

    public function post($endpoint, $params = [], $headers = []): ResponseInterface
    {

        $body = [
            'form_params' => $params,
        ];

        return $this->request($endpoint, $body, 'POST', $headers);
    }
}