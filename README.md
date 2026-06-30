<p align="center">
<a href="https://github.com/codebar-ag/laravel-template.git" target="_blank">
<img src="https://banners.beyondco.de/Laravel%20Template.png?theme=dark&pattern=architect&style=style_1&description=codebar+Solutions+AG&md=1&showWatermark=0&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg" alt="Laravel Logo">
</a>
</p>

## About Laravel Template

Laravel Template is a project template for all Codebar Solutions AG Laravel applications.
It is a starting point for new Laravel projects.
It includes a basic setup for a Laravel application with some common packages and configurations.:

- Auth
- Support for Microsoft Office 365 Authentication
- Enums
- [Laravel Nova](https://nova.laravel.com).
- [Flysystem Cloudinary](https://github.com/codebar-ag/laravel-flysystem-cloudinary).
- [Flysystem Cloudinary Nova](https://github.com/codebar-ag/laravel-flysystem-cloudinary-nova).
- [Spatie Activity Log](https://github.com/spatie/laravel-activitylog).
- [Spatie Health](https://github.com/spatie/laravel-health).
- [Spatie Permission](https://github.com/spatie/laravel-permission).

## Installation

You can use this template by clicking on the `Use this template` button on the top of this github repository page.

**INSERT IMAGE HERE* *

Once you have created a new repository from this template, you can clone it to your local machine and install the dependencies:

```bash
composer install

cp .env.example .env
cp vite.config-example.js vite.config.js

php artisan key:generate

php artisan migrate --seed

npm install
npm run build
```

If you want to use Valet to serve your application, you can run the following command:

```bash
valet link
 
valet secure

valet open
```

If you want to user Herd to serve your application, you can run the following command:

```bash
herd link
 
herd secure

herd open
```

You can run the development asset server with the following command:

```bash
npm run dev
````

> Note: You should set `valetTls: 'your-domain.test',` below `refresh: true,` in your `vite.config.js` file if you use `valet secure` or `herd secure`.

## Assets

Assets should be set in the following directories:

- `resources/js` for JavaScript files.
- `resources/css` for CSS files.
- `resources/fonts` for Font files.
- `resources/images` for Image files.

After you have added your assets, you can run the following command to compile them:
```bash
npm run build
```

To include your assets in your blade files, you can use the following:

```blade
{{ Vite::asset('resources/images/your-image.png') }}
```

## Auth

Auth is enabled by default.

You can configure auth settings in the `config/laravel-auth.php` file.

### 🔐 Verify
If you wish to use email verification, you can use the following middleware to protect your routes:

```php
Route::middleware(['laravel-auth-middleware'])->group(function () {
    ...
});
```
To use verification in nova, add the middleware into in your `nova.php` config:

```php
/*
|--------------------------------------------------------------------------
| Nova Route Middleware
|--------------------------------------------------------------------------
|
| These middleware will be assigned to every Nova route, giving you the
| chance to add your own middleware to this stack or override any of
| the existing middleware. Or, you can just stick with this stack.
|
*/

'middleware' => [
    'web',
    'laravel-auth-middleware',
    HandleInertiaRequests::class,
    DispatchServingNovaEvent::class,
    BootTools::class,
],
```

### Microsoft Office 365 Authentication

⚠️ When using Office 365 you need to provide a publicly accessible URL for the `MICROSOFT_REDIRECT_URI` environment variable. You can use [expose](https://expose.dev/) or [ngrok](https://ngrok.com/) for local development.

```bash 
APP_URL=https://your-expose-or-ngrok-url.com

# ✅ This is recommended for production as well:
MICROSOFT_REDIRECT_URI="${APP_URL}/auth/service/microsoft/redirect"
```

## Permissions

Please refer to the [Spatie Permission](https://github.com/spatie/laravel-permission) documentation for more information on how to use permissions.

## Enums

Enums are included in PHP's core functionality but we have some additional functionality to make them easier to use.

You can create an enum in the `app/Enums` directory:

```php
<?php

namespace App\Enums;

use App\Interfaces\LabelEnumInterface;use App\Traits\HasLabels;

enum EnvironmentEnum: string implements LabelEnumInterface
{
    use HasLabels;

    case PRODUCTION = 'production';
    case STAGING = 'staging';
    case LOCAL = 'local';

    public function label(): string
    {
        return match ($this) {
            EnvironmentEnum::PRODUCTION => __('Production'),
            EnvironmentEnum::STAGING => __('Staging'),
            EnvironmentEnum::LOCAL => __('Local'),
        };
    }
}
```

You should use the `HasLabels` trait to add a `label` method to your enum.
You should also implement the `LabelEnumInterface` interface to ensure that the `label` method is implemented.

The `label` method should return the label for the enum value.

You can use the enum in your code like this:

```php
// Native PHP Enum
$enum = EnvironmentEnum::PRODUCTION; // Enum Object
$name = EnvironmentEnum::PRODUCTION->name; // Enum Name (PRODUCTION)
$value = EnvironmentEnum::PRODUCTION->value; // Enum Value (production)

// Label
$label = EnvironmentEnum::PRODUCTION->label();  // Enum Label using Laravels Translation (Production)

// Labels
$labels = EnvironmentEnum::labels(); // Array of Enum Labels with Enum value as Key (['production' => 'Production', 'staging' => 'Staging', 'local' => 'Local'])
```

## Health

Please refer to the [Spatie Health](https://github.com/spatie/laravel-health) documentation for more information on how to use health checks.

We have added two health checks which are located in the `app/Checks` directory:

- `FailedJobsCheck`
- `JobsCheck`

## Helpers

We have added some helper functions which are located in the `app/Helpers` directory

You should the Facades for the helpers which are located in the `app/Helpers/Facades` directory:

- `HelperBank`
- `HelperDate`
- `HelperDevice`
- `HelperFile`
- `HelperMarkdown`
- `HelperMoney`
- `HelperNumber`
- `HelperPhone`

## Feature Policy

We have added feature policy which is located in the `app/FeaturePolicy` directory.

It is enabled by default and you can configure it in the `config/feature-policy.php` and `config/default.php` file.

The Feature Policy Middleware is installed and enabled by default in the `bootstrap/app.php` file.

## Traits

We have added some traits which are located in the `app/Traits` directory.

- `HassUuid`

We also have some traits which are located in the `app/Traits/Nova` directory which are intended for use only in Laravel Nova.

- `NovaCustomOrderTrait`
- `NovaIdentificationPanelTrait`
- `NovaLanguageTrait`
- `NovaTimestampsPanelTrait`

## Blade Components

We have added some blade components which are located in the `resources/views/components` directory.

- `fathom.blade.php`
- `favicons.blade.php`

You should include both the blade components in your blade layout files

## Laravel Cloud deployment

When deploying to Laravel Cloud, ensure these environment variables are set for Lighthouse Best Practices (security headers):

- `CSP_ENABLED=true` — enables Content-Security-Policy enforcement via Spatie CSP middleware
- `FPH_ENABLED=true` — enables Permissions-Policy headers

Security headers (HSTS, COOP, `X-Content-Type-Options`, `Referrer-Policy`, `X-Frame-Options`) are applied automatically by `SecurityHeaders` middleware on all web responses.

**Lighthouse note:** Deprecated API warnings for `/cdn-cgi/challenge-platform/scripts/jsd/main.js` come from Cloudflare bot protection injected by Laravel Cloud, not from application code. Run Lighthouse in incognito without browser extensions for accurate scores.

## Cloudinary

Please refer to the respective documentation for the Cloudinary and Cloudinary Nova packages.

- [Flysystem Cloudinary](https://github.com/codebar-ag/laravel-flysystem-cloudinary).
- [Flysystem Cloudinary Nova](https://github.com/codebar-ag/laravel-flysystem-cloudinary-nova).

## Notifications

We use Filament for notifications. Please refer to the [Filament](https://filamentphp.com/docs/3.x/notifications/sending-notifications) documentation for more information on how to use notifications.

## Pint

We use Laravel Pint to format code.

You can run the following command to format your code:

```bash
./vendor/bin/pint
```

You can run the following command to format your blade files:

```bash
./vendor/bin/pint --blade
```

> The blade formatter is still in beta and may not work as expected. if you wish to not use this update your `composer.json` to use the latest version of `laravel/pint` instead of the dev branch.


## Testing

We use PestPHP for testing.

You can run the following command to run your tests:

```bash
./vendor/bin/pest
```

Please refer to the [PestPHP](https://pestphp.com) documentation for more information on how to use PestPHP.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
