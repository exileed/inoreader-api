<?php declare(strict_types=1);


namespace ExileeD\Inoreader\Client;


use ExileeD\Inoreader\Exception\InoreaderException;
use Psr\Http\Message\ResponseInterface;

interface ClientInterface
{

    /**
     * Make an HTTP GET request to API
     *
     * @access public
     *
     * @param  string       $endpoint API endpoint
     * @param  string|array $params   GET parameters
     * @param  array        $headers  HTTP headers
     *
     * @throws InoreaderException
     * @return ResponseInterface
     */
    public function get($endpoint, $params = [], $headers = []): ResponseInterface;

    /**
     * Make an HTTP POST request to API
     *
     * @access public
     *
     * @param  string       $endpoint API endpoint
     * @param  string|array $params   POST parameters
     * @param  array        $headers  HTTP headers
     *
     * @throws InoreaderException
     * @return ResponseInterface
     */
    public function post($endpoint, $params = [], $headers = []): ResponseInterface;


    /**
     * Make a HTTP request
     *
     * @access public
     *
     * @param  string       $endpoint
     * @param  string|array $params
     * @param  string       $method
     * @param  array        $headers
     *
     * @throws InoreaderException
     * @return ResponseInterface
     */
    public function request($endpoint, $params = [], $method = 'GET', array $headers = []): ResponseInterface;


    /**
     * Construct API base URL based on current API version
     *
     * @access public
     * @return string
     */
    public function getApiBaseUrl(): string;

}