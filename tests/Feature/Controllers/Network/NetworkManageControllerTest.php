<?php

use App\Enums\LocaleEnum;
use App\Enums\NetworkStatusEnum;
use App\Jobs\Mail\NetworkProfileUpdatedMail;
use App\Models\Network;
use App\Models\NetworkUser;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

use function Pest\Laravel\get;
use function Pest\Laravel\put;
use function Pest\Laravel\travel;

function signedManageUrl(NetworkUser $networkUser): string
{
    return URL::temporarySignedRoute(
        'de-ch.network.manage.show',
        now()->addHours(48),
        ['networkUser' => $networkUser],
    );
}

function createNetworkWithUser(): NetworkUser
{
    foreach (LocaleEnum::cases() as $locale) {
        Network::factory()->create([
            'key' => 'docuware',
            'locale' => $locale->value,
            'name' => 'DocuWare',
            'website' => 'https://old.example.com',
        ]);
    }

    return NetworkUser::factory()->create([
        'network_key' => 'docuware',
        'name' => 'Vincenzo Carbone',
        'role' => 'DocuWare Schweiz',
        'email' => 'vincenzo@example.com',
        'published' => false,
    ]);
}

it('rejects the manage form without a valid signature', function () {
    $networkUser = createNetworkWithUser();

    get(route('de-ch.network.manage.show', ['networkUser' => $networkUser]))
        ->assertForbidden();
})->group('network');

it('rejects an expired manage link', function () {
    $networkUser = createNetworkWithUser();
    $url = signedManageUrl($networkUser);

    travel(49)->hours();

    get($url)->assertForbidden();
})->group('network');

it('shows the manage form with a valid signature', function () {
    $networkUser = createNetworkWithUser();

    get(signedManageUrl($networkUser))
        ->assertOk()
        ->assertSee('Vincenzo Carbone')
        ->assertSee('vincenzo@example.com')
        ->assertSee('DocuWare');
})->group('network');

it('updates the own profile and the company website via a signed link', function () {
    Mail::fake();

    $networkUser = createNetworkWithUser();

    put(signedManageUrl($networkUser), [
        'email' => 'vincenzo.carbone@docuware.com',
        'linkedin' => 'https://www.linkedin.com/in/vincenzo',
        'phone' => '+41 44 000 00 00',
        'published' => '1',
        'website' => 'https://start.docuware.com',
    ])->assertRedirect();

    $networkUser->refresh();

    expect($networkUser->email)->toBe('vincenzo.carbone@docuware.com')
        ->and($networkUser->linkedin)->toBe('https://www.linkedin.com/in/vincenzo')
        ->and($networkUser->phone)->toBe('+41 44 000 00 00')
        ->and($networkUser->published)->toBeTrue();

    Network::where('key', 'docuware')->get()->each(function (Network $network) {
        expect($network->website)->toBe('https://start.docuware.com');
    });

    Mail::assertSent(NetworkProfileUpdatedMail::class, function (NetworkProfileUpdatedMail $mail) {
        return $mail->hasTo(config('mail.from.address'));
    });
})->group('network');

it('can unpublish the own profile', function () {
    Mail::fake();

    $networkUser = createNetworkWithUser();
    $networkUser->update(['published' => true]);

    put(signedManageUrl($networkUser), [
        'email' => 'vincenzo@example.com',
        'published' => '0',
    ])->assertRedirect();

    expect($networkUser->refresh()->published)->toBeFalse();
})->group('network');

it('never touches fields outside the whitelist', function () {
    Mail::fake();

    $networkUser = createNetworkWithUser();

    put(signedManageUrl($networkUser), [
        'email' => 'vincenzo@example.com',
        'published' => '1',
        // Attempted privilege escalation: none of these may have any effect.
        'name' => 'Hacked Name',
        'role' => 'Hacked Role',
        'network_key' => 'other-company',
        'status' => NetworkStatusEnum::ENDED->value,
        'tier_label' => 'Platinum Partner',
        'sort' => 999,
    ])->assertRedirect();

    $networkUser->refresh();

    expect($networkUser->name)->toBe('Vincenzo Carbone')
        ->and($networkUser->role)->toBe('DocuWare Schweiz')
        ->and($networkUser->network_key)->toBe('docuware');

    Network::where('key', 'docuware')->get()->each(function (Network $network) {
        expect($network->name)->toBe('DocuWare')
            ->and($network->published)->toBeTrue()
            ->and($network->status)->toBe(NetworkStatusEnum::ACTIVE)
            ->and($network->tier_label)->toBeNull();
    });
})->group('network');

it('cannot change the company visibility via the signed link', function () {
    Mail::fake();

    $networkUser = createNetworkWithUser();

    put(signedManageUrl($networkUser), [
        'email' => 'vincenzo@example.com',
        'published' => '0',
    ])->assertRedirect();

    // Only the person's own visibility changed — the company stays published.
    Network::where('key', 'docuware')->get()->each(function (Network $network) {
        expect($network->published)->toBeTrue();
    });
})->group('network');

it('rejects an update without a valid signature', function () {
    Mail::fake();

    $networkUser = createNetworkWithUser();

    put(route('de-ch.network.manage.update', ['networkUser' => $networkUser]), [
        'email' => 'evil@example.com',
    ])->assertForbidden();

    expect($networkUser->refresh()->email)->toBe('vincenzo@example.com');

    Mail::assertNothingSent();
})->group('network');

it('validates linkedin and website urls', function () {
    Mail::fake();

    $networkUser = createNetworkWithUser();

    put(signedManageUrl($networkUser), [
        'linkedin' => 'not-a-url',
        'website' => 'also-not-a-url',
    ])->assertSessionHasErrors(['linkedin', 'website']);

    Mail::assertNothingSent();
})->group('network');
