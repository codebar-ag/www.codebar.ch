<?php

use App\Enums\LocaleEnum;
use App\Jobs\Network\SendNetworkInviteJob;
use App\Models\Network;
use App\Models\NetworkUser;
use Illuminate\Support\Facades\Bus;

use function Pest\Laravel\artisan;

function createInvitableUser(): NetworkUser
{
    foreach (LocaleEnum::cases() as $locale) {
        Network::factory()->create([
            'key' => 'docuware',
            'locale' => $locale->value,
            'name' => 'DocuWare',
        ]);
    }

    return NetworkUser::factory()->create([
        'network_key' => 'docuware',
        'name' => 'Vincenzo Carbone',
        'email' => 'vincenzo@example.com',
    ]);
}

it('requires a valid locale option', function () {
    Bus::fake();

    artisan('network:invite')->assertFailed();
    artisan('network:invite', ['--locale' => 'fr'])->assertFailed();

    Bus::assertNothingDispatched();
})->group('network');

it('queues an invite job per user after confirmation', function () {
    Bus::fake();

    $networkUser = createInvitableUser();

    artisan('network:invite', ['--locale' => 'de'])
        ->expectsConfirmation('Send 1 invitation(s) in de_CH?', 'yes')
        ->assertSuccessful();

    Bus::assertDispatched(SendNetworkInviteJob::class, function (SendNetworkInviteJob $job) use ($networkUser) {
        return $job->email === $networkUser->email && $job->locale === 'de_CH';
    });
})->group('network');

it('queues nothing when the confirmation is declined', function () {
    Bus::fake();

    createInvitableUser();

    artisan('network:invite', ['--locale' => 'en'])
        ->expectsConfirmation('Send 1 invitation(s) in en_CH?', 'no')
        ->assertSuccessful();

    Bus::assertNothingDispatched();
})->group('network');

it('can be limited to a single email address', function () {
    Bus::fake();

    createInvitableUser();
    NetworkUser::factory()->create(['network_key' => 'docuware', 'email' => 'other@example.com']);

    artisan('network:invite', ['--locale' => 'de', '--email' => 'vincenzo@example.com'])
        ->expectsConfirmation('Send 1 invitation(s) in de_CH?', 'yes')
        ->assertSuccessful();

    Bus::assertDispatched(SendNetworkInviteJob::class, 1);
    Bus::assertDispatched(SendNetworkInviteJob::class, fn (SendNetworkInviteJob $job) => $job->email === 'vincenzo@example.com');
})->group('network');

it('skips users without an email address', function () {
    Bus::fake();

    NetworkUser::factory()->create(['email' => null]);

    artisan('network:invite', ['--locale' => 'de'])->assertSuccessful();

    Bus::assertNothingDispatched();
})->group('network');
