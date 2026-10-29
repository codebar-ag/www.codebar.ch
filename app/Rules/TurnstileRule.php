<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

/**
 * Verifies a Cloudflare Turnstile token server-side.
 *
 * @see https://developers.cloudflare.com/turnstile/get-started/server-side-validation/
 */
class TurnstileRule implements ValidationRule
{
    public const string FIELD = 'cf-turnstile-response';

    public const string VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    /**
     * Run even when the field is missing, so a bot cannot skip the check by omitting the token.
     */
    public bool $implicit = true;

    /**
     * Adds the rule only when a secret is configured, so local development and CI
     * work without Cloudflare keys.
     *
     * @return array<string, list<self>>
     */
    public static function rules(): array
    {
        return filled(config('services.turnstile.secret_key'))
            ? [self::FIELD => [new self]]
            : [];
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || $value === '') {
            $fail(__('The spam check failed. Please reload the page and try again.'));

            return;
        }

        try {
            $response = Http::asForm()
                ->timeout(5)
                ->post(self::VERIFY_URL, [
                    'secret' => config('services.turnstile.secret_key'),
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ]);
        } catch (ConnectionException) {
            $fail(__('The spam check failed. Please reload the page and try again.'));

            return;
        }

        if ($response->json('success') !== true) {
            $fail(__('The spam check failed. Please reload the page and try again.'));
        }
    }
}
