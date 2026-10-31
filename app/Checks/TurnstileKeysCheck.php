<?php

declare(strict_types=1);

namespace App\Checks;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;
use Throwable;

/**
 * Verifies the Cloudflare Turnstile keys behind the protected forms. Sends a
 * dummy token to siteverify: a valid secret answers invalid-input-response,
 * a wrong one invalid-input-secret. Fails when the secret is rejected, when a
 * secret is set without a site key (the widget cannot render, so every submit
 * is rejected), when Cloudflare cannot be reached (the form rule rejects then
 * too) and when production runs on Cloudflare test keys. Reports ok when the
 * app does not use Turnstile.
 *
 * @see https://developers.cloudflare.com/turnstile/get-started/server-side-validation/
 */
final class TurnstileKeysCheck extends Check
{
    public const string VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    /** Real keys start with 0x; 1x, 2x and 3x are Cloudflare's test keys. */
    private const string PRODUCTION_KEY_PREFIX = '0x';

    public function run(): Result
    {
        $siteKey = $this->key('services.turnstile.site_key');
        $secretKey = $this->key('services.turnstile.secret_key');
        $result = Result::make();

        if ($siteKey === '' && $secretKey === '') {
            return $result->shortSummary('not configured')->ok();
        }

        if ($secretKey === '') {
            return $result->shortSummary('no secret key')
                ->warning('TURNSTILE_SITE_KEY is set without TURNSTILE_SECRET_KEY: the forms are not protected against spam.');
        }

        if ($siteKey === '') {
            return $result->shortSummary('no site key')
                ->failed('TURNSTILE_SECRET_KEY is set without TURNSTILE_SITE_KEY: the widget cannot render, so every form submit is rejected.');
        }

        if (app()->isProduction() && ! $this->isProductionKey($siteKey, $secretKey)) {
            return $result->shortSummary('test keys')
                ->failed('Production uses Cloudflare Turnstile test keys: they either let every bot through or reject every submit.');
        }

        try {
            $response = Http::asForm()->timeout(3)->post(self::VERIFY_URL, [
                'secret' => $secretKey,
                'response' => 'health-check',
            ]);
        } catch (Throwable $exception) {
            return $result->shortSummary('cloudflare unreachable')
                ->failed("Cloudflare Turnstile request failed, so form submits are rejected too: {$exception->getMessage()}");
        }

        $errors = $this->errorCodes($response);

        if (in_array('invalid-input-secret', $errors, true)) {
            return $result->shortSummary('secret rejected')
                ->failed('Cloudflare rejected TURNSTILE_SECRET_KEY, so every form submit is rejected.');
        }

        if ($response->json('metadata.result_with_testing_key') === true) {
            return $result->shortSummary('test keys')->ok();
        }

        if (in_array('invalid-input-response', $errors, true)) {
            return $result->shortSummary('secret valid')->ok();
        }

        return $result->shortSummary("http {$response->status()}")
            ->failed('Unexpected Cloudflare Turnstile answer: '.($errors === [] ? $response->status() : implode(', ', $errors)).'.');
    }

    private function key(string $configKey): string
    {
        $value = config($configKey);

        return is_string($value) ? trim($value) : '';
    }

    private function isProductionKey(string ...$keys): bool
    {
        foreach ($keys as $key) {
            if (! str_starts_with($key, self::PRODUCTION_KEY_PREFIX)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return list<string>
     */
    private function errorCodes(Response $response): array
    {
        $codes = $response->json('error-codes');

        return is_array($codes) ? array_values(array_filter($codes, is_string(...))) : [];
    }
}
