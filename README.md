# Inoreader PHP Client

[![Latest Version](https://img.shields.io/github/release/exileed/inoreader-api.svg?style=flat-square)](https://github.com/exileed/inoreader-api/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/exileed/inoreader-api/master.svg?style=flat-square&1)](https://travis-ci.org/exileed/inoreader-api)
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


$client->getLoginUrl($redirectUrl)

```

### Advanced usage

@todo

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