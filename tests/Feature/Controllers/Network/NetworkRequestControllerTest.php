<?php

use App\Jobs\Mail\NetworkManageLinkMail;
use App\Models\NetworkUser;
use Illuminate\Support\Facades\Mail;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('renders the request form for both locales', function (string $routeName) {
    get(route($routeName))->assertOk();
})->with(['de-ch.network.request.index', 'en-ch.network.request.index'])->group('network');

it('sends a signed manage link when the email is registered', function () {
    Mail::fake();

    $networkUser = NetworkUser::factory()->create([
        'email' => 'vincenzo@example.com',
    ]);

    post(route('de-ch.network.request.store'), ['email' => 'vincenzo@example.com'])
        ->assertRedirect(route('de-ch.network.request.index'))
        ->assertSessionHas('status', __('If the email address is registered, we have sent you a link.'));

    Mail::assertSent(NetworkManageLinkMail::class, function (NetworkManageLinkMail $mail) use ($networkUser) {
        return $mail->hasTo('vincenzo@example.com')
            && $mail->networkUser->is($networkUser)
            && str_contains($mail->url, 'signature=')
            && str_contains($mail->url, (string) $networkUser->id);
    });
})->group('network');

it('sends nothing for an unknown email but responds identically', function () {
    Mail::fake();

    post(route('de-ch.network.request.store'), ['email' => 'unknown@example.com'])
        ->assertRedirect(route('de-ch.network.request.index'))
        ->assertSessionHas('status', __('If the email address is registered, we have sent you a link.'));

    Mail::assertNothingSent();
})->group('network');

it('validates the email address', function () {
    Mail::fake();

    post(route('de-ch.network.request.store'), ['email' => 'not-an-email'])
        ->assertSessionHasErrors('email');

    Mail::assertNothingSent();
})->group('network');

it('throttles repeated requests', function () {
    Mail::fake();

    foreach (range(1, 5) as $i) {
        post(route('de-ch.network.request.store'), ['email' => 'unknown@example.com'])
            ->assertRedirect();
    }

    post(route('de-ch.network.request.store'), ['email' => 'unknown@example.com'])
        ->assertStatus(429);
})->group('network');
