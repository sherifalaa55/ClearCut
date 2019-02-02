# ClearCut
Laravel package for logging Http requests/responses to database. It provides a middleware that handles request logging and sets a header "X-Request-Id" with a uuid for tracking each request

## Features
* Handles Request Logging to Database
* Provides a UUID for each request for tracking/debugging
* Configurable to match or exclude certain requests

## Installation
Require the `sherifai/clearcut` package in your `composer.json` and update your dependencies:
```sh
$ composer require sherifai/clearcut
```
Next publish the configuration. This is an optional step, it contains some configurations that by default logs all requests.
```sh
$ php artisan vendor:publish
```
Next generate the migration file:
```sh
$ php artisan clearcut:migration
```
It will generate the <timestamp>_request_logs_table.php migration.

## Usage
To Start logging requests add LogRequests Middleware in Kernel.php
```php
protected $middleware = [
    // ...
    \SherifAI\ClearCut\Middleware\LogRequests::class,
];
```

## Configuration
The configuration file `config/clearcut.php` conatins values for selectively logging requests. By default ClearCut Logs all requests.

## Contribution
Support follows PSR-2 PHP coding standards.

Please report any issue you find in the issues page.
Pull requests are welcome.

## License
Released under the MIT License, see [LICENSE](LICENSE).
