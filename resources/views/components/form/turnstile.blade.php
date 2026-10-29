{{-- Cloudflare Turnstile bot check for public forms. Renders nothing without a site key,
     so local development and CI keep working; TurnstileRule verifies the token the widget
     writes into a hidden cf-turnstile-response input. Only use it on pages behind
     DoNotCacheResponse, or a cached copy would keep serving the old widget state. --}}
@if(filled(config('services.turnstile.site_key')))
    <div {{ $attributes->merge(['class' => 'mt-4']) }}>
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        <div class="cf-turnstile"
             data-sitekey="{{ config('services.turnstile.site_key') }}"
             data-language="{{ str(app()->getLocale())->before('_') }}"
             data-size="flexible"></div>

        @error(\App\Rules\TurnstileRule::FIELD)
            <p id="cf-turnstile-response-error" class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
@endif
