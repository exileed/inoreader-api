<?php
declare(strict_types=1);

namespace ExileeD\Inoreader\Test;

use ExileeD\Inoreader\Client;
use ExileeD\Inoreader\HttpClient\ClientInterface;
use ExileeD\Inoreader\HttpClient\GuzzleClient;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{

    /**
     * @var Client
     */
    private $client;


    public function setUp()
    {

        $guzzle = $this->getHttpClientMock();

        $client = $this->getMockBuilder(Client::class)
                       ->setMethods(['processResponse', 'get', 'post'])
                       ->setConstructorArgs([$guzzle])
                       ->getMock();


        $this->client = $client;

    }

    protected function getHttpClientMock()
    {
        return $this->getMockBuilder(GuzzleClient::class)
                    ->setMethods(['get', 'post', 'put', 'delete', 'request'])
                    ->getMock();
    }

    /** @test */
    public function it_checks_http_client()
    {

        $result =  $this->client->getHttpClient();

        self::assertInstanceOf(ClientInterface::class, $result);
    }


    /** @test */
    public function it_checks_post()
    {
        $result =  $this->client->post('test');

        self::assertInstanceOf(\stdClass::class, $result);
    }


    /** @test */
    public function it_checks_get()
    {
        $result =  $this->client->get('test');

        self::assertInstanceOf(\stdClass::class, $result);
    }







}
