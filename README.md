# Package to facilitate the connection with the Riot Games API in LOL

[![Latest Version on Packagist](https://img.shields.io/packagist/v/json-nan/laravel-riot-lol-api.svg?style=flat-square)](https://packagist.org/packages/json-nan/laravel-riot-lol-api)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/json-nan/laravel-riot-lol-api/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/json-nan/laravel-riot-lol-api/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/json-nan/laravel-riot-lol-api/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/json-nan/laravel-riot-lol-api/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/json-nan/laravel-riot-lol-api.svg?style=flat-square)](https://packagist.org/packages/json-nan/laravel-riot-lol-api)

This package is a wrapper for the Riot Games API in League of Legends, it is designed to facilitate the connection with the API and to be able to use the data in a more friendly way.

## Requirements

-   PHP 8.2 or higher
-   Laravel 10 or higher
-   Riot Games API Key from [Riot Games Developer](https://developer.riotgames.com/)

## Features
-   Get Summoner info
-   Get Player account info 

## Installation

You can install the package via composer:

```bash
composer require json-nan/laravel-riot-lol-api
```


You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-riot-lol-api-config"
```

## Configuration
It requires the following environment variables:
```
RIOT_LOL_API_KEY=
```

You can configure this optional environment variable:
```
RIOT_LOL_API_DOMAIN=
RIOT_LOL_REGION=
RIOT_LOL_PLATFORM=
RIOT_LOL_DATA_DRAGON_VERSION=
```

## Usage

```php
use JsonNaN\LaravelRiotLolApi\LaravelRiotLolApi;


$riotApi = new LaravelRiotLolApi();

$riotApi->getPlayerAccountByRiotId("Name#TAG")
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

-   [Jasson López](https://github.com/json-nan)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
