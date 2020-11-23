# Inoreader PHP Client

[![Latest Version](https://img.shields.io/packagist/v/exileed/inoreader-api)](https://packagist.org/packages/exileed/inoreader-api)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
![Build Status](https://img.shields.io/github/workflow/status/exileed/inoreader-api/test?style=flat-square&1
)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/exileed/inoreader-api.svg?style=flat-square)](https://scrutinizer-ci.com/g/exileed/inoreader-api/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/exileed/inoreader-api.svg?style=flat-square)](https://scrutinizer-ci.com/g/exileed/inoreader-api)
[![Total Downloads](https://img.shields.io/packagist/dt/exileed/inoreader-api.svg?style=flat-square)](https://packagist.org/packages/exileed/inoreader-api)

A PHP client for authenticating with Inoreader using OAuth and consuming the API.

## Install

Via Composer

``` bash
$ composer require exileed/inoreader-api
```

## Usage



#### Client Example

```php

use ExileeD\Inoreader\Inoreader;

$apiKey = 1000000;
$apiSecret = 'xxxx';
$token = 'ssss';


$inoreaderClient = new Inoreader( $apiKey, $apiSecret );

$inoreaderClient->setAccessToken($token);

$inoreaderClient->itemsIds();
```

#### Access token via Oauth2

```php

use ExileeD\Inoreader\Inoreader;

$apiKey = 1000000;
$apiSecret = 'xxxx';


$client = new Inoreader( $apiKey, $apiSecret );
$redirectUrl = 'http://localhost';
$scope = 'test';

$client->getLoginUrl($redirectUrl, $scope);

$client->accessTokenFromCode('code', $redirectUrl);

// Access token from refresh token
$client->accessTokenFromCode('code', $redirectUrl);
```

### Advanced usage

User info

```php
$client->userInfo();
```

Add subscription
```php
$url = 'https://www.inoreader.com/blog/feed';

$client->addSubscription($url);
``` 

Edit subscription

```php
$url = 'feed/https://www.inoreader.com/blog/feed';

$client->editSubscription(['ac' => 'edit', 's' => $url, 't' => 'test']));
```

Unread count 

```php
$client->unreadCount():
```

Subscription list

```php
$client->subscriptionList();
```

Folders and tags list

```php
use ExileeD\Inoreader\Objects\Tag;

$type = Tag::TYPE_ITEM;
//$type = Tag::TYPE_TAG;
//$type = Tag::TYPE_FOLDER;
//$type = Tag::TYPE_ACTIVE_SEARCH;

$client->tagsList($type, $count);

```



## Testing

``` bash
$ ./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Dmitriy Kuts](https://github.com/exileed)
- [All Contributors](https://github.com/exileed/inoreader-ap/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.