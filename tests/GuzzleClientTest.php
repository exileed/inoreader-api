<?php
declare(strict_types=1);

namespace ExileeD\Inoreader\Test;

use ExileeD\Inoreader\Client;
use ExileeD\Inoreader\Client\GuzzleClient;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class GuzzleClientTest extends TestCase
{

    /**
     * @var GuzzleClient
     */
    private $client;


    public function setUp()
    {


        $this->client = $this->getHttpClientMock();

    }

    protected function getHttpClientMock()
    {
        return $this->getMockBuilder(GuzzleClient::class)
                    ->setMethods(['get', 'post', 'put', 'delete', 'request'])
                    ->getMock();
    }

    /** @test */
    public function it_checks_instance_of_client()
    {
        $this->assertInstanceOf(Client\ClientInterface::class, $this->client);
    }

    /** @test */
    public function it_checks_instance_of_guzzle_client()
    {
        $result = $this->client->getClient();

        $this->assertInstanceOf(\GuzzleHttp\Client::class, $result);
    }

    /** @test */
    public function it_checks_api_base_url()
    {
        $url = 'https://www.inoreader.com/reader/api/0/';

        $result = $this->client->getApiBaseUrl();

        $this->assertEquals($url, $result);
    }

    /** @test */
    public function it_checks_set_client(){

        $guzzle = new \GuzzleHttp\Client();
        $this->client->setClient($guzzle);
        $result =  $this->client->getClient();

        $this->assertInstanceOf(\GuzzleHttp\Client::class, $result);

    }


    /** @test */
    public function it_checks_post_request()
    {

        $result = $this->client->post('test');

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }


    /** @test */
    public function it_checks_get()
    {

        $result = $this->client->get('test',[]);

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    /** @test */
    public function it_checks_request()
    {
        $result = $this->client->request('test');

        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

//
//    /** @test */
//    public function it_checks_get()
//    {
//
//        $result =  $this->client->get('test');
//
//        $this->assertInstanceOf(\stdClass::class, $result);
//    }


}
