Currency Exchanger
=============================

![Workflow Build Status](https://github.com/fractalzombie/frzb-currency-exchanger/actions/workflows/build.yml/badge.svg?event=push)

The `Currency Exchanger` allows you to rate currencies

Installation
------------
The recommended way to install is through Composer:

```
composer install
```

It requires PHP version 8.3 and higher.

Usage of `Currency Exchanger`

Example of .env file
```
###> docker ###
UID=1000
GID=1000
COMPOSE_FILE=docker-compose.yml
###< docker ###

###< proxy ###
PROXY_HTTP_PORT=8080
PROXY_HTTPS_PORT=8443
###> proxy ###

###> system ###
PHP_CS_FIXER_IGNORE_ENV=true
###< system ###

###> symfony/framework-bundle ###
APP_RUNTIME=Symfony\Component\Runtime\GenericRuntime
APP_NAME=currency-exchanger
APP_ENV=dev
APP_SECRET=30f787921c5ac438ea4a19442bddf17d
###< symfony/framework-bundle ###

###> currency-provider ###
PROVIDER_EXTERNAL_URL=https://developers.paysera.com/tasks/api/currency-exchange-rates
###< currency-provider ###

###> bin-provider ###
PROVIDER_BINLIST_LOCAL_DATETIME_CACHE_FORMAT=dmY
PROVIDER_BINLIST_HTTP_URL=https://lookup.binlist.net
###< bin-provider ###
```

```
make start
```

Usage
------------
API: http://localhost:8080/api/v1/exchange/

Request:
```json
{
    "amountList": [
        {
            "bin": "45717360",
            "amount": "100.00",
            "currency": "EUR"
        },
        {
            "bin": "516793",
            "amount": "50.00",
            "currency": "USD"
        },
        {
            "bin": "45417360",
            "amount": "10000.00",
            "currency": "JPY"
        },
        {
            "bin": "41417360",
            "amount": "130.00",
            "currency": "USD"
        },
        {
            "bin": "4745030",
            "amount": "2000.00",
            "currency": "GBP"
        }
    ]
}
```

Response:
```json
[
    {
        "bin": 45717360,
        "amount": 1
    },
    {
        "bin": 516793,
        "amount": 0.44285763632708053
    },
    {
        "bin": 45417360,
        "amount": 1.5282343940505163
    },
    {
        "bin": 41417360,
        "error": "BIN info is not provided by external service for \"41417360\""
    },
    {
        "bin": 4745030,
        "amount": 23.942289505376237
    }
]
```
Command:
```bash
bin/console currency:exchange /full/path/to/file
```
Formats: `text` or `json`
```json
[
    {"bin": "45717360", "amount": "100.00", "currency": "EUR"},
    {"bin": "516793", "amount": "50.00", "currency": "USD"},
    {"bin": "45417360", "amount": "10000.00", "currency": "JPY"},
    {"bin": "41417360", "amount": "130.00", "currency": "USD"},
    {"bin": "4745030", "amount": "2000.00", "currency": "GBP"}
]

```
```text
{"bin":"45717360","amount":"100.00","currency":"EUR"}
{"bin":"516793","amount":"50.00","currency":"USD"}
{"bin":"45417360","amount":"10000.00","currency":"JPY"}
{"bin":"41417360","amount":"130.00","currency":"USD"}
{"bin":"4745030","amount":"2000.00","currency":"GBP"}
```

result:
```html
[OK] BIN: 45717360 exchanged: 1
[OK] BIN: 516793 exchanged: 0.44285763632708
[OK] BIN: 45417360 exchanged: 1.5282343940505
[ERROR] BIN info is not provided by external service for "41417360"
[OK] BIN: 4745030 exchanged: 23.942289505376
```

Resources
---------
* [License](https://github.com/fractalzombie/frzb-currency-exchanger/blob/main/LICENSE.md)
