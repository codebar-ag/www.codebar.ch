<?php

use App\Enums\LocaleEnum;
use App\Enums\NetworkStatusEnum;
use App\Models\Network;
use App\Models\NetworkUser;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

use function Pest\Laravel\get;
use function Pest\Laravel\put;
use function Pest\Laravel\travel;

function signedManageUrl(NetworkUser $networkUser): string
{
    return URL::temporarySignedRoute(
        'de-ch.network.manage.show',
        now()->addHour(),
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

    travel(2)->hours();

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
    $networkUser = createNetworkWithUser();

    put(signedManageUrl($networkUser), [
        'name' => 'Vincenzo Carbone-Rossi',
        'linkedin' => 'https://www.linkedin.com/in/vincenzo',
        'phone' => '+41 44 000 00 00',
        'published' => '1',
        'website' => 'https://start.docuware.com',
    ])->assertRedirect();

    $networkUser->refresh();

    expect($networkUser->name)->toBe('Vincenzo Carbone-Rossi')
        ->and($networkUser->linkedin)->toBe('https://www.linkedin.com/in/vincenzo')
        ->and($networkUser->phone)->toBe('+41 44 000 00 00')
        ->and($networkUser->published)->toBeTrue();

    Network::where('key', 'docuware')->get()->each(function (Network $network) {
        expect($network->website)->toBe('https://start.docuware.com');
    });
})->group('network');

it('can unpublish the own profile', function () {
    $networkUser = createNetworkWithUser();
    $networkUser->update(['published' => true]);

    put(signedManageUrl($networkUser), [
        'name' => 'Vincenzo Carbone',
        'published' => '0',
    ])->assertRedirect();

    expect($networkUser->refresh()->published)->toBeFalse();
})->group('network');

it('never touches fields outside the whitelist, including the email address', function () {
    $networkUser = createNetworkWithUser();

    put(signedManageUrl($networkUser), [
        'name' => 'Vincenzo Carbone',
        'published' => '1',
        // Attempted privilege escalation: none of these may have any effect.
        'email' => 'hacked@example.com',
        'role' => 'Hacked Role',
        'network_key' => 'other-company',
        'status' => NetworkStatusEnum::ENDED->value,
        'tier_label' => 'Platinum Partner',
        'sort' => 999,
    ])->assertRedirect();

    $networkUser->refresh();

    expect($networkUser->email)->toBe('vincenzo@example.com')
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
    $networkUser = createNetworkWithUser();

    put(signedManageUrl($networkUser), [
        'name' => 'Vincenzo Carbone',
        'published' => '0',
    ])->assertRedirect();

    // Only the person's own visibility changed — the company stays published.
    Network::where('key', 'docuware')->get()->each(function (Network $network) {
        expect($network->published)->toBeTrue();
    });
})->group('network');

it('rejects an update without a valid signature', function () {
    $networkUser = createNetworkWithUser();

    put(route('de-ch.network.manage.update', ['networkUser' => $networkUser]), [
        'name' => 'Evil Name',
    ])->assertForbidden();

    expect($networkUser->refresh()->name)->toBe('Vincenzo Carbone');
})->group('network');

it('requires a name', function () {
    $networkUser = createNetworkWithUser();

    put(signedManageUrl($networkUser), [
        'name' => '',
    ])->assertSessionHasErrors('name');
})->group('network');

it('validates linkedin and website urls', function () {
    $networkUser = createNetworkWithUser();

    put(signedManageUrl($networkUser), [
        'name' => 'Vincenzo Carbone',
        'linkedin' => 'not-a-url',
        'website' => 'also-not-a-url',
    ])->assertSessionHasErrors(['linkedin', 'website']);
})->group('network');

it('stores an uploaded avatar on s3 and saves its disk and path', function () {
    Storage::fake('s3');

    $networkUser = createNetworkWithUser();

    put(signedManageUrl($networkUser), [
        'name' => 'Vincenzo Carbone',
        'avatar' => UploadedFile::fake()->image('avatar.jpg', 200, 200),
    ])->assertRedirect();

    $files = Storage::disk('s3')->files('network/avatars');
    $networkUser->refresh();

    expect($files)->toHaveCount(1)
        ->and($files[0])->toStartWith('network/avatars/'.$networkUser->id.'-')
        ->and($networkUser->avatar_disk)->toBe('s3')
        ->and($networkUser->avatar_path)->toBe($files[0]);
})->group('network');

it('cannot change the read-only avatar and cover urls via the signed link', function () {
    $networkUser = createNetworkWithUser();
    $networkUser->update(['avatar_url' => 'https://res.cloudinary.com/demo/image/upload/avatar.jpg']);
    Network::where('key', 'docuware')->update(['cover_url' => 'https://res.cloudinary.com/demo/image/upload/cover.jpg']);

    put(signedManageUrl($networkUser), [
        'name' => 'Vincenzo Carbone',
        'avatar_url' => 'https://evil.example.com/avatar.jpg',
        'cover_url' => 'https://evil.example.com/cover.jpg',
    ])->assertRedirect();

    expect($networkUser->refresh()->avatar_url)
        ->toBe('https://res.cloudinary.com/demo/image/upload/avatar.jpg');

    Network::where('key', 'docuware')->get()->each(function (Network $network) {
        expect($network->cover_url)->toBe('https://res.cloudinary.com/demo/image/upload/cover.jpg');
    });
})->group('network');

it('stores an uploaded company cover on s3 for every locale row', function () {
    Storage::fake('s3');

    $networkUser = createNetworkWithUser();

    put(signedManageUrl($networkUser), [
        'name' => 'Vincenzo Carbone',
        'cover' => UploadedFile::fake()->image('cover.jpg', 1200, 600),
    ])->assertRedirect();

    $files = Storage::disk('s3')->files('network/covers');

    expect($files)->toHaveCount(1)
        ->and($files[0])->toStartWith('network/covers/docuware-');

    Network::where('key', 'docuware')->get()->each(function (Network $network) use ($files) {
        expect($network->cover_disk)->toBe('s3')
            ->and($network->cover_path)->toBe($files[0]);
    });
})->group('network');

it('rejects a non-image cover upload', function () {
    Storage::fake('s3');

    $networkUser = createNetworkWithUser();

    put(signedManageUrl($networkUser), [
        'name' => 'Vincenzo Carbone',
        'cover' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
    ])->assertSessionHasErrors('cover');

    expect(Storage::disk('s3')->files('network/covers'))->toBeEmpty();
})->group('network');

it('rejects a cover upload that is not 2:1', function () {
    Storage::fake('s3');

    $networkUser = createNetworkWithUser();

    put(signedManageUrl($networkUser), [
        'name' => 'Vincenzo Carbone',
        'cover' => UploadedFile::fake()->image('cover.jpg', 800, 600),
    ])->assertSessionHasErrors('cover');

    expect(Storage::disk('s3')->files('network/covers'))->toBeEmpty();
})->group('network');

it('rejects a non-image avatar upload', function () {
    Storage::fake('s3');

    $networkUser = createNetworkWithUser();

    put(signedManageUrl($networkUser), [
        'name' => 'Vincenzo Carbone',
        'avatar' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
    ])->assertSessionHasErrors('avatar');

    expect(Storage::disk('s3')->files('network/avatars'))->toBeEmpty();
})->group('network');

it('rejects a non-square avatar upload', function () {
    Storage::fake('s3');

    $networkUser = createNetworkWithUser();

    put(signedManageUrl($networkUser), [
        'name' => 'Vincenzo Carbone',
        'avatar' => UploadedFile::fake()->image('avatar.jpg', 300, 200),
    ])->assertSessionHasErrors('avatar');

    expect(Storage::disk('s3')->files('network/avatars'))->toBeEmpty();
})->group('network');

it('rejects an avatar upload above 2 MB', function () {
    Storage::fake('s3');

    $networkUser = createNetworkWithUser();

    put(signedManageUrl($networkUser), [
        'name' => 'Vincenzo Carbone',
        'avatar' => UploadedFile::fake()->image('big.jpg', 200, 200)->size(3000),
    ])->assertSessionHasErrors('avatar');

    expect(Storage::disk('s3')->files('network/avatars'))->toBeEmpty();
})->group('network');

it('keeps the stored avatar file when no new file is uploaded', function () {
    Storage::fake('s3');

    $networkUser = createNetworkWithUser();
    $networkUser->update([
        'avatar_disk' => 's3',
        'avatar_path' => 'network/avatars/existing.jpg',
        'avatar_url' => 'https://res.cloudinary.com/demo/image/upload/existing.jpg',
    ]);

    put(signedManageUrl($networkUser), [
        'name' => 'Vincenzo Carbone',
    ])->assertRedirect();

    $networkUser->refresh();

    expect($networkUser->avatar_disk)->toBe('s3')
        ->and($networkUser->avatar_path)->toBe('network/avatars/existing.jpg')
        ->and($networkUser->avatar_url)->toBe('https://res.cloudinary.com/demo/image/upload/existing.jpg');
})->group('network');
