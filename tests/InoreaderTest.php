<?php
declare(strict_types=1);

namespace ExileeD\Inoreader\Test;

use ExileeD\Inoreader\Client;
use ExileeD\Inoreader\Client\GuzzleClient;
use ExileeD\Inoreader\Inoreader;
use ExileeD\Inoreader\Objects\AddSubscription;
use ExileeD\Inoreader\Objects\ItemIds;
use ExileeD\Inoreader\Objects\StreamContents;
use ExileeD\Inoreader\Objects\Subscriptions;
use ExileeD\Inoreader\Objects\Tag;
use ExileeD\Inoreader\Objects\UnreadCount;
use ExileeD\Inoreader\Objects\UserInfo;
use PHPUnit\Framework\TestCase;

class InoreaderTest extends TestCase
{

    /**
     * @var Inoreader
     */
    private $client;


    public function setUp()
    {


        $guzzle = $this->getHttpClientMock();

        $client = $this->getMockBuilder(Client::class)
                       ->setMethods(['processResponse', 'get', 'post'])
                       ->setConstructorArgs([$guzzle])
                       ->getMock();


        $this->client = new Inoreader(1, 'test', $guzzle);

        $this->client->setClient($client);

    }

    protected function getHttpClientMock()
    {
        return $this->getMockBuilder(GuzzleClient::class)
                    ->setMethods(['get', 'post', 'put', 'delete', 'request'])
                    ->getMock();
    }

    /** @test */
    public function it_check_access_token()
    {

        $this->client->setAccessToken('test');

        $token = $this->client->getAccessToken();

        $this->assertEquals('test', $token);

    }


    /** @test */
    public function it_checks_auth_url()
    {

        $url = 'https://www.inoreader.com/oauth2/auth?client_id=1&redirect_uri=http%3A%2F%2Flocalhost&response_type=code&scope=&state=1';

        $result = $this->client->authUrl('http://localhost', '1');

        $this->assertEquals($url, $result);
    }

    /** @test */
    public function it_checks_token()
    {


        $this->markTestSkipped('skipped');

        $result = $this->client->token('123', 'http://localhost');

        $this->assertEquals('', $result);
    }


    /** @test */
    public function it_checks_the_default_http_client_is_guzzle_if_not_specified()
    {
        $client = $this->client->getClient()->getHttpClient();
        $this->assertInstanceOf(GuzzleClient::class, $client);
    }

    /** @test */
    public function it_checks_the_client_object_is_returned()
    {
        $this->assertInstanceOf(Client::class, $this->client->getClient());
    }


    /** @test */
    public function it_checks_the_user_info()
    {

        $result = $this->client->userInfo();

        $this->assertInstanceOf(UserInfo::class, $result);
    }

    /** @test */
    public function it_checks_the_add_subscription()
    {

        $url = 'http://localhost/feed';

        $result = $this->client->addSubscription($url);

        $this->assertInstanceOf(AddSubscription::class, $result);
    }

    /** @test */
    public function it_checks_the_unread_count()
    {

        $result = $this->client->unreadCount();

        $this->assertInstanceOf(UnreadCount::class, $result);
    }

    /** @test */
    public function it_checks_the_subscription_list()
    {

        $result = $this->client->subscriptionList();

        $this->assertInstanceOf(Subscriptions::class, $result);
    }

    /** @test */
    public function it_checks_the_subscription_edit()
    {
        $result = $this->client->editSubscription([]);

        $this->assertTrue($result);
    }


    /** @test */
    public function it_checks_the_tags_list()
    {

        $this->markTestSkipped('todo');
        $result = $this->client->tagsList();

        $this->assertInstanceOf(Tag::class, $result[ 0 ]);
    }


    /** @test */
    public function it_checks_the_stream_contents()
    {

        $result = $this->client->streamContents();

        $this->assertInstanceOf(StreamContents::class, $result);
    }

    /** @test */
    public function it_checks_the_items_ids()
    {

        $result = $this->client->itemsIds();

        $this->assertInstanceOf(ItemIds::class, $result);
    }


    /** @test */
    public function it_checks_the_rename_tag()
    {
        $result = $this->client->renameTag('a', 'b');

        $this->assertTrue($result);
    }

    /** @test */
    public function it_checks_the_delete_tag()
    {
        $result = $this->client->deleteTag('a');

        $this->assertTrue($result);
    }

    /** @test */
    public function it_checks_the_mark_all_as_read()
    {
        $result = $this->client->markAllAsRead(1234, 'feed');

        $this->assertTrue($result);
    }


}
