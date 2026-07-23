<?php

use App\Jobs\Network\SendNetworkInviteJob;
use App\Models\Network;
use App\Models\NetworkUser;
use App\Notifications\NetworkInviteNotification;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\get;
use function Pest\Laravel\travel;

function createInviteJobUser(): NetworkUser
{
    Network::factory()->create([
        'key' => 'docuware',
        'name' => 'DocuWare',
    ]);

    return NetworkUser::factory()->create([
        'network_key' => 'docuware',
        'name' => 'Vincenzo Carbone',
        'email' => 'vincenzo@example.com',
    ]);
}

it('sends the invitation with a 96 hour signed link and the company name', function () {
    Notification::fake();

    $networkUser = createInviteJobUser();

    (new SendNetworkInviteJob('vincenzo@example.com', 'de_CH'))->handle();

    Notification::assertSentTo($networkUser, NetworkInviteNotification::class, function (NetworkInviteNotification $notification) {
        expect($notification->company)->toBe('DocuWare')
            ->and($notification->url)->toContain('/netzwerk/verwalten/')
            ->and($notification->locale)->toBe('de_CH');

        // Still valid shortly before 96 hours, expired after.
        travel(95)->hours();
        expect(get($notification->url)->status())->toBe(200);
        travel(2)->hours();
        expect(get($notification->url)->status())->toBe(403);

        return true;
    });
})->group('network');

it('does nothing for an unknown email address', function () {
    Notification::fake();

    createInviteJobUser();

    (new SendNetworkInviteJob('unknown@example.com', 'de_CH'))->handle();

    Notification::assertNothingSent();
})->group('network');

it('logs the invitation to the notifications table', function () {
    $networkUser = createInviteJobUser();

    (new SendNetworkInviteJob('vincenzo@example.com', 'de_CH'))->handle();

    expect($networkUser->notifications()->count())->toBe(1)
        ->and($networkUser->notifications()->firstOrFail()->type)->toBe(NetworkInviteNotification::class);
})->group('network');
