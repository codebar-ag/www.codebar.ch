# JSON Body Guard

Rejects JSON request bodies before your PHP application decodes them, except on paths you allow-list. It
closes the hash-collision denial-of-service hole (HashDoS) that `max_input_vars` does not cover. The core
has no dependencies and works with Laravel, Symfony or any front controller. A small optional service
provider wires the config file into Laravel.

## Why

PHP's string hash is unseeded, so a JSON body with a few thousand colliding keys makes `json_decode` and
every array copy that follows quadratic. One request can pin a PHP-FPM worker for a minute, and a dozen
requests per minute take a site down. `max_input_vars` only caps form and query parsing, never JSON.

Laravel makes this worse: `Request::capture()` in `public/index.php` decodes the JSON body eagerly, before
routing and before any middleware, so the route does not matter and middleware cannot help. Symfony decodes
on demand (`$request->toArray()`, `#[MapRequestPayload]`), but every such endpoint carries the same risk.

The guard runs before the framework boots, costs one header lookup per request and never reads more than
the configured limit from the body.

## Installation

```bash
composer require codebar-ag/json-body-guard
```

## Configuration

The allow list lives in a plain PHP file that the guard loads before the framework starts:

```php
// config/json-body-guard.php
return [
    'enabled' => true,
    'max_bytes' => 64 * 1024,
    'allow' => [
        '/webhooks/*',
        '/api/graphql',
    ],
];
```

Laravel registers the package provider automatically. Publish the file with

```bash
php artisan vendor:publish --tag=json-body-guard-config
```

The same values are then also available as `config('json-body-guard.allow')` once Laravel has booted. Keep
the file free of `env()` calls, since `.env` is not loaded yet when the guard reads it.

## Usage

### Laravel

In `public/index.php`, right after the Composer autoloader and before `Request::capture()`:

```php
use CodebarAg\JsonBodyGuard\JsonBodyGuard;

require __DIR__.'/../vendor/autoload.php';

JsonBodyGuard::fromConfigFile(__DIR__.'/../config/json-body-guard.php')->guard();

(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
```

### Symfony

In `public/index.php`, before the runtime takes over:

```php
use CodebarAg\JsonBodyGuard\JsonBodyGuard;

require dirname(__DIR__).'/vendor/autoload.php';

JsonBodyGuard::fromConfigFile(dirname(__DIR__).'/config/json-body-guard.php')->guard();

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';
```

### Any PHP front controller

```php
JsonBodyGuard::allow('/webhooks/*')->maxBytes(64 * 1024)->guard();
```

or, for a site without any JSON endpoint:

```php
JsonBodyGuard::denyAll()->guard();
```

### Rules

| Request | Result |
|---|---|
| No JSON content type (forms, multipart, GET) | passes, nothing is read |
| JSON content type with an empty body | passes |
| JSON body on a path outside the allow list | `415 Unsupported Media Type` |
| JSON body on an allowed path, larger than the limit | `413 Payload Too Large` |
| JSON body on an allowed path, within the limit | passes |
| `enabled` set to `false` | everything passes |

A content type counts as JSON when it contains `/json` or `+json`, which is the rule Laravel's
`Request::isJson()` applies. The limit defaults to 64 KB. The body size comes from `Content-Length`; when
that header is missing, the guard reads at most the limit plus one byte from `php://input`.

Patterns match the URL path without the query string. A `*` matches any characters, including slashes, so
`/webhooks/*` covers `/webhooks/postmark` and `/webhooks/stripe/events` but not `/webhooks` itself.
Trailing slashes are ignored.

### Inspecting without exiting

`inspect(array $server)` returns a `Rejection` (with `reason`, `path`, `status()` and `message()`) or
`null`, and never sends output. `guard()` calls it, sends a plain-text response and exits.

## Limitations

- The guard protects PHP-FPM and the built-in server, where every request passes through the front
  controller. Octane and FrankenPHP worker mode build the request elsewhere, so the guard does not run there.
- Form and multipart bodies are not inspected. PHP's `max_input_vars` already caps those.
- Rejections happen before the framework boots, so they show up in the web server's access log, not in the
  application log.

## Testing

```bash
vendor/bin/pest --testsuite=Packages
```

## Credits

- [Roland Brand](https://www.linkedin.com/in/roland-brand-95a28b108/) (rolandbrand11@gmail.com), who demonstrated the attack against our site and prompted this package
- [Sebastian Bürgin-Fix](https://github.com/codebar-ag)
- [All contributors](https://github.com/codebar-ag/json-body-guard/contributors)

## Changelog

See [CHANGELOG](CHANGELOG.md).

## License

The MIT License (MIT). See [LICENSE](LICENSE.md).
