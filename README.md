# php-json-logger
LoggingLibrary for PHP. Output by JSON Format

## Getting Started

### Install composer package

```
composer require nekonomokochan/php-json-logger
```

## How To Use

### Basic usage

```php
<?php
use Nekonomokochan\PhpJsonLogger\LoggerBuilder;

$context = [
    'title' => 'Test',
    'price' => 4000,
    'list'  => [1, 2, 3],
    'user'  => [
        'id'   => 100,
        'name' => 'keitakn',
    ],
];

$loggerBuilder = new LoggerBuilder();
$logger = $loggerBuilder->build();
$logger->info('🐱', $context);
```

It is output as follows.

```json
{
    "log_level": "INFO",
    "message": "🐱",
    "trace_id": "35b627ce-55e0-4729-9da0-fbda2a7d817d",
    "file": "\/home\/vagrant\/php-json-logger\/tests\/LoggerTest.php",
    "line": 42,
    "context": {
        "title": "Test",
        "price": 4000,
        "list": [
            1,
            2,
            3
        ],
        "user": {
            "id": 100,
            "name": "keitakn"
        }
    },
    "remote_ip_address": "127.0.0.1",
    "user_agent": "unknown",
    "datetime": "2018-06-04 17:21:03.631409",
    "timezone": "Asia\/Tokyo",
    "process_time": 631.50811195373535
}
```
