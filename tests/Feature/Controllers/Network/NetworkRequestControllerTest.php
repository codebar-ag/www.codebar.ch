<?php

use App\Enums\LocaleEnum;
use App\Jobs\Network\SendNetworkManageLinkJob;
use App\Models\NetworkUser;
use App\Notifications\NetworkManageLinkNotification;
use Illuminate\Support\Facades\Bus;
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
