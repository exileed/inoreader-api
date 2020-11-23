<?php

declare(strict_types=1);

namespace ExileeD\Inoreader\Test;

use ExileeD\Inoreader\Objects\{
    ActiveSearch,
    AddSubscription,
    Subscription,
    Subscriptions,
    Token,
    UnreadCount,
    UserInfo
};
use ExileeD\Inoreader\HttpClient\GuzzleHttpClient;
use ExileeD\Inoreader\Inoreader;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class InoreaderTest extends TestCase
{
    /**
     * @var string
     */
    private const APP_ID = 111;
    /**
     * @var string
     */
    private const APP_KEY = 'token';
    /**
     * @var Inoreader
     */
    private $api;

    public function setUp(): void
    {
        $mock = new MockHandler(
            [
                new Response(200, [], 'OK'),
            ]
        );

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $httpClient = new GuzzleHttpClient($client);

        $this->api = new Inoreader(self::APP_ID, self::APP_KEY, $httpClient);
    }

    public function testCheckIfAccessTokenSet(): void
    {
        $token = 'token';
        $this->api->setAccessToken($token);

        self::assertSame($token, $this->api->getAccessToken());
    }

    public function testCheckGetLoginUrl(): void
    {
        $redirectUrl     = 'https://localhost/callback';
        $state           = 'test';
        $urlFromResponse = $this->api->getLoginUrl($redirectUrl, $state);

        $url = sprintf(
            'https://www.inoreader.com/oauth2/auth?client_id=%s&redirect_uri=%s&response_type=code&scope=&state=%s',
            self::APP_ID,
            urlencode($redirectUrl),
            $state
        );

        self::assertSame($urlFromResponse, $url);
    }

    public function testCheckAccessTokenFromCode(): void
    {
        $redirectUrl = 'https://localhost/callback';

        $requestBody = [
            'access_token' => 'token',
            'token_type' => 'Bearer',
            'expires_in' => 123,
            'refresh_token' => 'refresh',
            'scope' => '',
        ];

        $api = $this->makeInoreaderClient(new Response(200, [], json_encode($requestBody)));

        $response = $api->accessTokenFromCode('code', $redirectUrl);

        self::assertInstanceOf(Token::class, $response);
        self::assertSame('token', $response->accessToken());
        self::assertSame('Bearer', $response->tokenType());
        self::assertSame(123, $response->expiresIn());
        self::assertSame('refresh', $response->refreshToken());
        self::assertSame('', $response->scope());
    }

    private function makeInoreaderClient(Response $response): Inoreader
    {
        $mock = new MockHandler(
            [
                $response,
            ]
        );

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $httpClient = new GuzzleHttpClient($client);

        return new Inoreader(self::APP_ID, self::APP_KEY, $httpClient);
    }

    public function testCheckUserInfo(): void
    {
        $requestBody = [
            'userId' => '100100',
            'userName' => 'userName',
            'userProfileId' => '100100',
            'userEmail' => 'test@example.com',
            'isBloggerUser' => false,
            'signupTimeSec' => 1369039075,
            'isMultiLoginEnabled' => false,
        ];

        $api = $this->makeInoreaderClient(new Response(200, [], json_encode($requestBody)));

        $response = $api->userInfo();

        self::assertInstanceOf(UserInfo::class, $response);
        self::assertSame('100100', $response->userId());
        self::assertSame('userName', $response->userName());
        self::assertSame('100100', $response->userProfileId());
        self::assertSame('test@example.com', $response->userEmail());
        self::assertFalse($response->isBloggerUser());
        self::assertSame(1369039075, $response->signupTimeSec());
        self::assertFalse($response->isMultiLoginEnabled());
    }

    public function testCheckaddSubscription(): void
    {
        $requestBody = [
            'query' => 'https://habr.ru/feed',
            'numResults' => 1,
            'streamId' => 'feed/https://habr.ru/feed',
            'streamName' => 'Habr',
        ];

        $api = $this->makeInoreaderClient(new Response(200, [], json_encode($requestBody)));

        $url = 'https://habr.ru/feed';

        $response = $api->addSubscription($url);

        self::assertInstanceOf(AddSubscription::class, $response);
        self::assertSame('https://habr.ru/feed', $response->query());
        self::assertSame(1, $response->numResults());
        self::assertSame('feed/https://habr.ru/feed', $response->streamId());
        self::assertSame('Habr', $response->streamName());
    }

    public function testCheckEditSubscription(): void
    {
        $api = $this->makeInoreaderClient(new Response(200, [], 'OK'));

        $response = $api->editSubscription(['ac' => 'edit', 's' => 'feed/https://habr.ru/rss', 't' => 'test']);

        self::assertTrue($response);
    }

    public function testCheckUnreadCount(): void
    {
        $requestBody = [
            'max' => 1000,
            'unreadcounts' => [
                [
                    'id' => 'user/10000/state/com.google/starred',
                    'count' => 1000,
                    'newestItemTimestampUsec' => '1604005196587202',
                ],
            ],
        ];

        $api = $this->makeInoreaderClient(new Response(200, [], json_encode($requestBody)));

        $response = $api->unreadCount();

        self::assertInstanceOf(UnreadCount::class, $response);
        self::assertSame(1000, $response->max());
        self::assertSame('user/10000/state/com.google/starred', $response->unreadCounts()[ 0 ]->id());
        self::assertSame(1000, $response->unreadCounts()[ 0 ]->count());
        self::assertSame('1604005196587202', $response->unreadCounts()[ 0 ]->newestItemTimestampUsec());
    }

    public function testCheckSubscriptionList(): void
    {
        $requestBody = [
            'subscriptions' => [
                [
                    'id' => 'feed/http://localhost/feed',
                    'feedType' => 'rss',
                    'title' => 'Test News',
                    'categories' => [
                        [
                            'id' => 'user/1005913670/label/feed',
                            'label' => 'feed',
                        ],
                    ],
                    'sortid' => '00A71F77',
                    'firstitemmsec' => 1602722718000000,
                    'url' => 'http://localhost/blog/1',
                    'htmlUrl' => 'http://localhost/blog/1.html',
                    'iconUrl' => 'https://www.inoreader.com/fetch_icon/localhost?w=16&cs=958902693&v=3',
                ],
            ],
        ];

        $api = $this->makeInoreaderClient(new Response(200, [], json_encode($requestBody)));

        $response = $api->subscriptionList();

        $subscription = $response->subscriptions()[ 0 ];

        $category = $subscription->categories()[ 0 ];

        self::assertInstanceOf(Subscriptions::class, $response);
        self::assertSame('feed/http://localhost/feed', $subscription->id());
        self::assertSame('http://localhost/blog/1', $subscription->url());
        self::assertSame('rss', $subscription->feedType());
        self::assertSame('00A71F77', $subscription->sortId());
        self::assertSame('Test News', $subscription->title());
        self::assertSame('user/1005913670/label/feed', $category->id());
        self::assertSame('feed', $category->label());
        self::assertSame(1602722718000000, $subscription->firstItemMsec());
        self::assertSame('http://localhost/blog/1.html', $subscription->htmlUrl());
    }

    public function testCheckStreamPreferenceSet(): void
    {
        $api = $this->makeInoreaderClient(new Response(200, [], 'OK'));

        $response = $api->streamPreferenceSet('feed/http://localhost', '', '');

        self::assertTrue($response);
    }

    public function testCheckTagsList(): void
    {
        $requestBody = [
            'tags' => [
                [
                    'id' => 'user/100000/label/amazing',
                    'sortid' => 'BF6234C3',
                    'unread_count' => 0,
                    'unseen_count' => 0,
                    'type' => 'tag',
                ],
            ],
        ];

        $api = $this->makeInoreaderClient(new Response(200, [], json_encode($requestBody)));

        $response = $api->tagsList();

        $tag = $response[ 0 ];

        self::assertSame('user/100000/label/amazing', $tag->id());
        self::assertSame('BF6234C3', $tag->sortId());
        self::assertSame(0, $tag->unreadCount());
        self::assertSame(0, $tag->unseenCount());
        self::assertSame('tag', $tag->type());
    }

    public function testCheckStreamContents(): void
    {
        $requestBody = [
            'direction' => 'ltr',
            'id' => 'user/-/label/Google',
            'title' => 'Reading List',
            'description' => '',
            'self' =>
                [
                    'href' => 'https://www.inoreader.com',
                ],
            'updated' => 1424637593,
            'updatedUsec' => '1424637593264558',
            'items' =>
                [
                    [
                        'crawlTimeMsec' => '1422046342882',
                        'timestampUsec' => '1422046342881684',
                        'id' => 'tag:google.com,2005:reader/item/00000000f8b9270e',
                        'categories' =>
                            [
                                'user/1005921515/state/com.google/reading-list',
                                'user/1005921515/state/com.google/read',
                                'user/1005921515/label/Google',
                            ],
                        'title' => 'Through the Google lens',
                        'published' => 1422046320,
                        'updated' => 1422669611,
                        'canonical' =>
                            [
                                [
                                    'href' => '
                                    http://feedproxy.google.com',
                                ],
                            ],
                        'alternate' =>
                            [
                                [
                                    'href' => 'http://feedproxy.google.com',
                                    'type' => 'text/html',
                                ],
                            ],
                        'summary' =>
                            [
                                'direction' => 'ltr',
                                'content' => '...',
                            ],
                        'author' => 'Emily Wood',
                        'likingUsers' => [],
                        'comments' => [],
                        'commentsNum' => -1,
                        'annotations' => [],
                        'origin' =>
                            [
                                'streamId' => 'feed/http://feeds.feedburner.com/blogspot/MKuf',
                                'title' => 'The Official Google Blog',
                                'htmlUrl' => 'http://googleblog.blogspot.com/',
                            ],
                    ],
                ],
            'continuation' => 'trMnkg7wWT62',
        ];

        $api = $this->makeInoreaderClient(new Response(200, [], json_encode($requestBody)));

        $response = $api->streamContents('http://localhost/feed');

        $item = $response->items()[0];

        self::assertSame('user/-/label/Google', $response->id());
        self::assertSame('ltr', $response->direction());
        self::assertSame('Reading List', $response->title());
        self::assertSame('https://www.inoreader.com', $response->self()->href());
        self::assertSame(1424637593, $response->updated());
        self::assertSame('1424637593264558', $response->updatedUsec());

        self::assertSame('1422046342882', $item->crawlTimeMsec());
        self::assertSame('1422046342881684', $item->timestampUsec());
        self::assertSame('tag:google.com,2005:reader/item/00000000f8b9270e', $item->id());
        self::assertSame('Through the Google lens', $item->title());
        self::assertSame('Emily Wood', $item->author());


        self::assertSame('trMnkg7wWT62', $response->continuation());
    }

    public function testCheckItemsIds(): void
    {
        self::markTestSkipped('todo');

        $requestBody = [
            'id' => 'user/100000/label/amazing',
            'title' => 'amazing',
        ];

        $api = $this->makeInoreaderClient(new Response(200, [], json_encode($requestBody)));

        $response = $api->itemsIds();
    }

    public function testCheckStreamPreferenceList(): void
    {
        self::markTestSkipped('todo');
        $requestBody = [
            'id' => 'user/100000/label/amazing',
            'title' => 'amazing',
        ];

        $api = $this->makeInoreaderClient(new Response(200, [], json_encode($requestBody)));

        $response = $api->streamPreferenceList();
    }


    public function testCheckRenameTag(): void
    {
        $api = $this->makeInoreaderClient(new Response(200, [], 'OK'));

        $response = $api->renameTag('oldTag', 'newTag');

        self::assertTrue($response);
    }

    public function testCheckDeleteTag(): void
    {
        $api = $this->makeInoreaderClient(new Response(200, [], 'OK'));

        $response = $api->deleteTag('oldTag');

        self::assertTrue($response);
    }

    public function testCheckEditTag(): void
    {
        $api = $this->makeInoreaderClient(new Response(200, [], 'OK'));

        $response = $api->editTag(['tag:google.com,2005:reader/item/0000000623507a3c'], 'user/-/state/com.google/read');

        self::assertTrue($response);
    }

    public function testMarkAllAsRead(): void
    {
        $api = $this->makeInoreaderClient(new Response(200, [], 'OK'));

        $response = $api->markAllAsRead(100, 'feed/http://localhost');

        self::assertTrue($response);
    }

    public function testCheckAccessTokenFromRefreshToken(): void
    {
        $requestBody = [
            'access_token' => 'token',
            'token_type' => 'Bearer',
            'expires_in' => 123,
            'refresh_token' => 'refresh',
            'scope' => '',
        ];

        $api = $this->makeInoreaderClient(new Response(200, [], json_encode($requestBody)));

        $response = $api->accessTokenFromRefresh('token');

        self::assertInstanceOf(Token::class, $response);
        self::assertSame('token', $response->accessToken());
        self::assertSame('Bearer', $response->tokenType());
        self::assertSame(123, $response->expiresIn());
        self::assertSame('refresh', $response->refreshToken());
        self::assertSame('', $response->scope());
    }


    public function testCheckCreateActiveSearchResponseOk(): void
    {
        $requestBody = [
            'id' => 'user/100000/label/amazing',
            'title' => 'amazing',
        ];

        $api = $this->makeInoreaderClient(new Response(200, [], json_encode($requestBody)));

        $response = $api->createActiveSearch([]);

        self::assertInstanceOf(ActiveSearch::class, $response);
        self::assertSame('user/100000/label/amazing', $response->id());
        self::assertSame('amazing', $response->title());
    }


    public function testCheckDeleteActiveSearchResponseOk(): void
    {
        $response = $this->api->deleteActiveSearch('test');

        self::assertTrue($response);
    }
}
