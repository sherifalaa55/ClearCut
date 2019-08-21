# ClearCut
Laravel package for logging Http requests/responses to database. It provides a middleware that handles request logging and sets a header "X-Request-Id" with a uuid for tracking each request. All logging is done in the background so it won't affect response time.

## Features
* Handles Request Logging to Database as a background job
* Provides a UUID for each request for tracking/debugging
* Dumps log table to laravel storage periodically
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
It will generate the &lt;timestamp&gt;_request_logs_table.php migration.

## Usage
To Start logging requests add LogRequests Middleware in Kernel.php
```php
protected $middleware = [
    // ...
    \SherifAI\ClearCut\Middleware\LogRequests::class,
];
```

Clearcut then pushes the logging process to a background job.

## Configuration
The configuration file `config/clearcut.php` conatins values for selectively logging requests. By default ClearCut Logs all requests.
The most important configurations however are

1. `'dump_every' => 1000` which specifies the interval for logging requests before dumping the database.

2. `'storage_disk' => 'local'` which specifies the storage location for the dump file

3. `'queue_name' => 'clearcut'` which is the default queue for processing log job

4. `'enabled' => true` which determines of the package is working and storing request logs

## Contribution
Support follows PSR-2 PHP coding standards.

Please report any issue you find in the issues page.
Pull requests are welcome.

## License
Released under the MIT License, see [LICENSE](LICENSE).
