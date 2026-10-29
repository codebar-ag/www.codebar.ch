<?php

declare(strict_types=1);

use App\Enums\JobPositionStatusEnum;
use App\Jobs\Network\SendNetworkManageLinkJob;
use App\Models\Application;
use App\Models\JobPosition;
use App\Rules\TurnstileRule;
use Illuminate\Support\Facades\Bus;
use Pest\Browser\Api\PendingAwaitablePage;

use function Pest\Laravel\assertDatabaseCount;

/*
 * Runs against the real Cloudflare Turnstile API with Cloudflare's documented test keys,
 * which work on any hostname: https://developers.cloudflare.com/turnstile/troubleshooting/testing/
 */
const TURNSTILE_TEST_SITE_KEY = '1x00000000000000000000AA';
const TURNSTILE_TEST_SECRET_PASSES = '1x0000000000000000000000000000000AA';
const TURNSTILE_TEST_SECRET_FAILS = '2x0000000000000000000000000000000AA';

function visitWithTurnstile(string $path, string $secret): PendingAwaitablePage
{
    config([
        'honeypot.valid_from_timestamp' => false,
        'services.turnstile.site_key' => TURNSTILE_TEST_SITE_KEY,
        'services.turnstile.secret_key' => $secret,
    ]);

    $page = visit($path);

    // The widget solves the challenge asynchronously and writes the token into a hidden input.
    $token = 'document.querySelector(\'input[name="'.TurnstileRule::FIELD.'"]\')?.value';

    foreach (range(1, 20) as $attempt) {
        if (filled($page->script($token))) {
            break;
        }

        $page->wait(0.5);
    }

    $page->assertScript($token.'.length > 0');

    return $page;
}

it('sends the network link once turnstile has passed', function () {
    Bus::fake([SendNetworkManageLinkJob::class]);

    visitWithTurnstile('/netzwerk/request', TURNSTILE_TEST_SECRET_PASSES)
        ->type('email', 'vincenzo@example.com')
        ->click('main form button[type="submit"]')
        ->assertSee(__('If the email address is registered, we have sent you a link.', [], 'de_CH'))
        ->assertNoJavaScriptErrors();

    Bus::assertDispatched(SendNetworkManageLinkJob::class);
})->group('browser', 'turnstile');

it('rejects the internship request when cloudflare refuses the token', function () {
    JobPosition::factory()->create([
        'key' => Application::JOB_KEY_INTERNSHIP,
        'status' => JobPositionStatusEnum::Open,
        'route_name' => 'jobs.internship.show',
    ]);

    visitWithTurnstile('/stellen/praktikum', TURNSTILE_TEST_SECRET_FAILS)
        ->type('input[name="email"]', 'mina@example.com')
        ->click('form[action$="/stellen/praktikum"] button[type="submit"]')
        ->assertSee(__('The spam check failed. Please reload the page and try again.', [], 'de_CH'));

    assertDatabaseCount('applications', 0);
})->group('browser', 'turnstile');
