<?php declare(strict_types=1);


namespace ExileeD\Inoreader;


use ExileeD\Inoreader\HttpClient\ClientInterface;
use ExileeD\Inoreader\Exception\InoreaderException;
use ExileeD\Inoreader\Objects\AddSubscription;
use ExileeD\Inoreader\Objects\ItemIds;
use ExileeD\Inoreader\Objects\StreamContents;
use ExileeD\Inoreader\Objects\StreamPreferenceList;
use ExileeD\Inoreader\Objects\Subscriptions;
use ExileeD\Inoreader\Objects\Tag;
use ExileeD\Inoreader\Objects\Token;
use ExileeD\Inoreader\Objects\UnreadCount;
use ExileeD\Inoreader\Objects\UserInfo;

class Inoreader
{

    private const API_OAUTH = 'https://www.inoreader.com/oauth2/';

    /**
     * Api key
     *
     * @var string
     */
    private $apiKey;
    /**
     * @var string
     */
    protected $api_secret;
    /**
     * @var Client
     */
    protected $client;

    public function __construct(int $apiKey, string $api_secret, ClientInterface $client = null)
    {
        $this->apiKey    = $apiKey;
        $this->api_secret = $api_secret;

        $this->client = new Client($client);
    }


    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->getClient()->getAccessToken();
    }

    /**
     * @access public
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @access public
     *
     * @param  Client $client
     *
     * @return $this
     */
    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @param string $access_token
     */
    public function setAccessToken(string $access_token): void
    {
        $this->getClient()->setAccessToken($access_token);
    }

    /**
     * @param string $redirect_uri This is the address that the user will be redirected to when he authorizes your application from the consent page.
     * @param string $scope        You can pass read or read write
     * @param string $state        Up to 500 bytes of arbitrary data that will be passed back to your redirect URI.
     *
     * @see https://www.inoreader.com/developers/oauth
     * @return string
     */
    public function getLoginUrl(string $redirect_uri, string $state, string $scope = ''): string
    {

        $query = [
            'client_id'     => $this->apiKey,
            'redirect_uri'  => $redirect_uri,
            'response_type' => 'code',
            'scope'         => $scope,
            'state'         => $state,
        ];

        return self::API_OAUTH . 'auth' . '?' . http_build_query($query);
    }

    /**
     *  Refreshing an access token
     *
     * @param string $code
     * @param string $redirect_uri
     *
     * @see http://www.inoreader.com/developers/oauth
     * @throws InoreaderException
     * @return Token
     */
    public function accessTokenFromCode(string $code, string $redirect_uri): Token
    {

        $params = [
            'code'          => $code,
            'redirect_uri'  => $redirect_uri,
            'client_id'     => $this->apiKey,
            'client_secret' => $this->api_secret,
            'scope'         => '',
            'grant_type'    => 'authorization_code',
        ];

        $response = $this->getClient()->post(self::API_OAUTH . 'token', $params);

        return new Token($response);
    }

    /**
     *  Refreshing an access token
     *
     * @param string $refresh_token
     *
     * @see http://www.inoreader.com/developers/oauth
     * @throws InoreaderException
     * @return Token
     */
    public function accessTokenFromRefresh(string $refresh_token): Token
    {

        $params = [
            'client_id'     => $this->apiKey,
            'client_secret' => $this->api_secret,
            'refresh_token' => $refresh_token,
            'grant_type'    => 'refresh_token',
        ];

        $response = $this->getClient()->post(self::API_OAUTH . 'token', $params);

        return new Token($response);
    }

    /**
     * Basic information about the logged in user.
     *
     * @see https://www.inoreader.com/developers/user-info
     * @throws InoreaderException
     * @return UserInfo
     */
    public function userInfo(): UserInfo
    {
        $response = $this->getClient()->get('user-info');

        return new UserInfo($response);
    }

    /**
     * This method is used to subscribe to feeds.
     *
     * @param string $url feedId to subscribe to
     *
     * @see https://www.inoreader.com/developers/add-subscription
     * @throws InoreaderException
     * @return AddSubscription
     */
    public function addSubscription(string $url): AddSubscription
    {
        $params   = [
            'quickadd' => $url,
        ];
        $response = $this->getClient()->post('subscription/quickadd', $params);

        return new AddSubscription($response);
    }

    /**
     * This method is used to rename the subscription, add it to a folder, remove it from folder or unsubscribe from it.
     *
     * @param string $params ['ac'] Action. Can be edit, subscribe, or unsubscribe.
     * @param string $params ['s']  Stream ID
     * @param string $params ['t']  Subscription title.
     * @param string $params ['a']  Add subscription from folder.
     * @param string $params ['r']  Remove subscription from folder.
     *
     * @see http://www.inoreader.com/developers/edit-subscription
     * @throws InoreaderException
     * @return bool
     */
    public function editSubscription(array $params): bool
    {

        $this->getClient()->post('subscription/edit', $params);

        return true;
    }


    /**
     * Fetch the unread counters for folders, tags and feeds.
     *
     * @see https://www.inoreader.com/developers/unread-counts
     * @throws InoreaderException
     * @return UnreadCount
     */
    public function unreadCount(): UnreadCount
    {
        $response = $this->getClient()->post('unread-count');

        return new UnreadCount($response);
    }


    /**
     * Fetches the current subscriptions for the logged user
     *
     * @see http://www.inoreader.com/developers/subscription-list
     * @throws InoreaderException
     * @return Subscriptions
     */
    public function subscriptionList(): Subscriptions
    {

        $response = $this->getClient()->get('subscription/list');

        return new Subscriptions($response);

    }


    /**
     * Folders and tags list
     *
     * @param int|string $types  Set to 1 to get the item type. Can be tag, folder or active_search
     * @param int        $counts Set to 1 to get unread counts for tags and active searches.
     *
     * @see http://www.inoreader.com/developers/tag-list
     * @throws InoreaderException
     * @return Tag[]
     */
    public function tagsList($types = 1, $counts = 1): array
    {

        $response = $this->getClient()->get('tag/list', ['types' => $types, 'counts' => $counts]);

        $result = [];

        foreach ($response->tags as $tag) {
            $result[] = new Tag($tag);
        }

        return $result;

    }

    /**
     * Returns the articles for a given collection.
     *
     * @param array $params
     *
     * @see http://www.inoreader.com/developers/stream-contents
     * @throws InoreaderException
     * @return StreamContents
     */
    public function streamContents(array $params = []): StreamContents
    {

        $response = $this->getClient()->get('stream/contents', $params);

        return new StreamContents($response);

    }


    /**
     * This method is used to return only the article ids for a given stream.
     *
     * @param array $params
     *
     * @see http://www.inoreader.com/developers/stream-contents
     * @throws InoreaderException
     * @return ItemIds
     */
    public function itemsIds(array $params = []): ItemIds
    {

        $response = $this->getClient()->get('stream/items/ids', $params);

        return new ItemIds($response);

    }


    /**
     * List of folders and the system.
     *
     *
     * @see http://www.inoreader.com/developers/preference-list
     * @throws InoreaderException
     * @return StreamPreferenceList
     */
    public function streamPreferenceList(): StreamPreferenceList
    {

        $response = $this->getClient()->get('preference/stream/list');

        return new StreamPreferenceList($response);

    }


    /**
     * List of folders and the system.
     *
     * @param string $stream_id Stream ID
     * @param string $key       Key Only accepted is subscription-ordering
     * @param string $value     Value.
     *
     * @see http://www.inoreader.com/developers/preference-set
     * @throws InoreaderException
     * @return bool
     */
    public function streamPreferenceSet(string $stream_id, $key = null, $value = null): bool
    {
        $this->getClient()->post('preference/stream/set',
            [
                's' => $stream_id,
                'k' => $key,
                'v' => $value,
            ]
        );

        return true;

    }


    /**
     * This method is used to rename tags and folders
     *
     * @param string $source Source name
     * @param string $target Target name
     *
     * @see http://www.inoreader.com/developers/rename-tag
     * @throws InoreaderException
     * @return bool
     */
    public function renameTag(string $source, string $target): bool
    {
        $this->getClient()->post('rename-tag',
            [
                's'    => $source,
                'dest' => $target,
            ]
        );

        return true;
    }


    /**
     * This method is used to delete tags and folders.
     *
     * @param string $source Full tag name
     *
     * @see http://www.inoreader.com/developers/delete-tag
     * @throws InoreaderException
     * @return bool
     */
    public function deleteTag(string $source): bool
    {
        $this->getClient()->post('disable-tag',
            [
                's' => $source,
            ]
        );

        return true;
    }

    /**
     * This method is used to mark articles as read, or to star them.
     *
     * @param array  $item  Item ID
     * @param string $add    Tag to add
     * @param string $remove Tag to remove
     *
     * @see http://www.inoreader.com/developers/edit-tag
     * @throws InoreaderException
     * @return bool
     */
    public function editTag($item, string $add = null, string $remove = null): bool
    {


        $params = [
            'i' => $item,
            'a' => $add,
            'r' => $remove,
        ];

        $this->getClient()->post('edit-tag', $params);

        return true;
    }


    /**
     * This method marks all items in a given stream as read.
     *
     * @param int    $timestamp Unix Timestamp in seconds or microseconds.
     * @param string $stream_id Stream ID
     *
     * @see http://www.inoreader.com/developers/mark-all-as-read
     * @throws InoreaderException
     * @return bool
     */
    public function markAllAsRead(int $timestamp, string $stream_id): bool
    {

        $this->getClient()->get('mark-all-as-read', [
            'ts' => $timestamp,
            's'  => $stream_id,
        ]);

        return true;
    }


}