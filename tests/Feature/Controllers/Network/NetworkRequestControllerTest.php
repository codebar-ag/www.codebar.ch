<?php

declare(strict_types=1);

use App\Enums\LocaleEnum;
use App\Jobs\Network\SendNetworkManageLinkJob;
use App\Models\NetworkUser;
use App\Notifications\NetworkManageLinkNotification;
use App\Rules\TurnstileRule;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('renders the request form for both locales', function (string $routeName) {
    get(route($routeName))->assertOk();
})->with(['de-ch.network.request.index', 'en-ch.network.request.index'])->group('network');

it('dispatches the manage link job with the submitted email and current locale', function () {
    Bus::fake();

    post(route('de-ch.network.request.store'), ['email' => 'vincenzo@example.com'])
        ->assertRedirect(route('de-ch.network.request.index'));

    Bus::assertDispatched(SendNetworkManageLinkJob::class, function (SendNetworkManageLinkJob $job) {
        return $job->email === 'vincenzo@example.com'
            && $job->locale === LocaleEnum::DE->value;
    });
})->group('network');

it('sends a signed manage link when the email is registered', function () {
    Notification::fake();

    $networkUser = NetworkUser::factory()->create([
        'email' => 'vincenzo@example.com',
    ]);

    post(route('de-ch.network.request.store'), ['email' => 'vincenzo@example.com'])
        ->assertRedirect(route('de-ch.network.request.index'))
        ->assertSessionHas('status', __('If the email address is registered, we have sent you a link.'));

    Notification::assertSentTo(
        $networkUser,
        NetworkManageLinkNotification::class,
        function (NetworkManageLinkNotification $notification) use ($networkUser) {
            return str_contains($notification->url, 'signature=')
                && str_contains($notification->url, (string) $networkUser->id);
        },
    );
})->group('network');

it('logs the manage link notification to the notifications table', function () {
    $networkUser = NetworkUser::factory()->create([
        'email' => 'vincenzo@example.com',
    ]);

    post(route('de-ch.network.request.store'), ['email' => 'vincenzo@example.com'])
        ->assertRedirect(route('de-ch.network.request.index'));

    expect($networkUser->notifications()->where('type', NetworkManageLinkNotification::class)->count())->toBe(1);
})->group('network');

it('sends nothing for an unknown email but responds identically', function () {
    Notification::fake();

    post(route('de-ch.network.request.store'), ['email' => 'unknown@example.com'])
        ->assertRedirect(route('de-ch.network.request.index'))
        ->assertSessionHas('status', __('If the email address is registered, we have sent you a link.'));

    Notification::assertNothingSent();
})->group('network');

it('validates the email address', function () {
    Notification::fake();

    post(route('de-ch.network.request.store'), ['email' => 'not-an-email'])
        ->assertSessionHasErrors('email');

    Notification::assertNothingSent();
})->group('network');

it('throttles repeated requests', function () {
    Notification::fake();

    foreach (range(1, 5) as $i) {
        post(route('de-ch.network.request.store'), ['email' => 'unknown@example.com'])
            ->assertRedirect();
    }

    post(route('de-ch.network.request.store'), ['email' => 'unknown@example.com'])
        ->assertStatus(429);
})->group('network');

/**
 * The only unauthenticated POST on the site. The response is deliberately identical
 * whether or not the address is registered, so enumeration costs a bot nothing but
 * requests — the honeypot is what makes those requests unprofitable.
 */
it('drops a submission with the honeypot field filled', function () {
    Bus::fake();

    post(route('de-ch.network.request.store'), [
        'email' => 'bot@example.com',
        config()->string('honeypot.name_field_name') => 'i am a bot',
    ]);

    Bus::assertNotDispatched(SendNetworkManageLinkJob::class);
})->group('network');

it('renders the turnstile widget when a site key is configured', function () {
    config(['services.turnstile.site_key' => 'test-site-key']);

    get(route('en-ch.network.request.index'))
        ->assertOk()
        ->assertSee('challenges.cloudflare.com/turnstile/v0/api.js', false)
        ->assertSee('data-sitekey="test-site-key"', false)
        ->assertSee('data-language="en"', false);
})->group('network', 'turnstile');

it('omits the turnstile widget without a site key', function () {
    config(['services.turnstile.site_key' => null]);

    get(route('de-ch.network.request.index'))
        ->assertOk()
        ->assertDontSee('cf-turnstile', false);
})->group('network', 'turnstile');

it('dispatches the manage link job when the turnstile token is valid', function () {
    Bus::fake();
    config(['services.turnstile.secret_key' => 'test-secret']);
    Http::fake([TurnstileRule::VERIFY_URL => Http::response(['success' => true])]);

    post(route('de-ch.network.request.store'), [
        'email' => 'vincenzo@example.com',
        TurnstileRule::FIELD => 'valid-token',
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('de-ch.network.request.index'));

    Bus::assertDispatched(SendNetworkManageLinkJob::class);
    Http::assertSent(fn (Request $request) => $request['secret'] === 'test-secret'
        && $request['response'] === 'valid-token');
})->group('network', 'turnstile');

it('rejects the request when the turnstile token is missing, rejected or unverifiable', function (array $payload, Closure $fake) {
    Bus::fake();
    config(['services.turnstile.secret_key' => 'test-secret']);
    $fake();

    post(route('de-ch.network.request.store'), ['email' => 'vincenzo@example.com', ...$payload])
        ->assertSessionHasErrors([TurnstileRule::FIELD => __('The spam check failed. Please reload the page and try again.')]);

    Bus::assertNotDispatched(SendNetworkManageLinkJob::class);
})->with([
    'missing' => [[], fn () => Http::fake()],
    'rejected' => [[TurnstileRule::FIELD => 'forged-token'], fn () => Http::fake([TurnstileRule::VERIFY_URL => Http::response(['success' => false])])],
    'unreachable' => [[TurnstileRule::FIELD => 'valid-token'], fn () => Http::fake(fn () => throw new ConnectionException('timeout'))],
])->group('network', 'turnstile');

it('skips the turnstile check when no secret is configured', function () {
    Bus::fake();
    config(['services.turnstile.secret_key' => null]);
    Http::fake();

    post(route('de-ch.network.request.store'), ['email' => 'vincenzo@example.com'])
        ->assertSessionHasNoErrors();

    Bus::assertDispatched(SendNetworkManageLinkJob::class);
    Http::assertNothingSent();
})->group('network', 'turnstile');
