<?php

declare(strict_types=1);

namespace ExileeD\Inoreader\HttpClient;

use ExileeD\Inoreader\Exception\InoreaderException;
use Psr\Http\Message\ResponseInterface;

interface HttpClient
{
    /**
     * Make a HTTP request
     *
     * @access public
     *
     * @param string       $endpoint
     * @param string|array $params
     * @param string       $method
     * @param array        $headers
     *
     * @return ResponseInterface
     * @throws InoreaderException
     */
    public function request(string $endpoint, array $params = [], string $method = 'GET', array $headers = []):
    ResponseInterface;
}
