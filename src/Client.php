<?php declare(strict_types=1);

namespace ExileeD\Inoreader;


use ExileeD\Inoreader\Client\ClientInterface;
use ExileeD\Inoreader\Client\GuzzleClient;
use ExileeD\Inoreader\Exception\InoreaderException;
use Psr\Http\Message\ResponseInterface;

class Client
{

    /**
     * @var ClientInterface
     */
    private $http_client;


    /** @var string|null */
    private $access_token = null;

    /**
     * Instantiates a new Client object.
     *
     * @param ClientInterface|null $http_client_handler
     */
    public function __construct(ClientInterface $http_client_handler = null)
    {
        $this->http_client = $http_client_handler ?? new GuzzleClient();
    }

    /**
     * Makes a POST request to the Inoreader API and returns the response
     *
     * @param string $endpoint
     * @param array  $params
     *
     * @throws InoreaderException
     * @return \stdClass|bool
     */
    public function post(string $endpoint, $params = [])
    {

        $headers = $this->headers();

        $response = $this->getHttpClient()->post($endpoint, $params, $headers);

        return $this->processResponse($response);

    }

    private function headers(): array
    {

        $headers = [];
        if ($this->getAccessToken() !== null) {
            $headers[ 'Authorization' ] = 'Bearer ' . $this->getAccessToken();
        }

        return $headers;

    }

    /**
     * @return string
     */
    public function getAccessToken(): ?string
    {
        return $this->access_token;
    }

    /**
     * @param string $access_token
     */
    public function setAccessToken(string $access_token): void
    {
        $this->access_token = $access_token;
    }

    /**
     * Returns the inoreader client's http client to the given http client. Client.
     *
     * @return  ClientInterface
     */
    public function getHttpClient(): ClientInterface
    {
        return $this->http_client;
    }

    /**
     * Makes a request to the Inoreader API and returns the response
     *
     * @param    ResponseInterface $response
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

        return json_decode($content);
    }

    /**
     * Makes a GET request to the Inoreader API and returns the response
     *
     * @param string $endpoint
     * @param array  $params
     *
     * @throws InoreaderException
     * @return \stdClass
     */
    public function get(string $endpoint, $params = [])
    {

        $headers = $this->headers();

        $response = $this->getHttpClient()->get($endpoint, $params, $headers);

        return $this->processResponse($response);

    }

}