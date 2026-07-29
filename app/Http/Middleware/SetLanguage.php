<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Actions\LocaleAction;
use App\Enums\LocaleEnum;
use App\Enums\SessionKeyEnum;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class SetLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $locale = $this->localeFromRoute($request) ?? $this->localeFromSession($request);

        (new LocaleAction($locale))->setLocale();

        return $next($request);
    }

    /**
     * The URL is the single source of truth for the language: every localized
     * route name carries its locale prefix (e.g. "en-ch.", "de-ch."), so crawlers
     * without a session always get the language the URL promises.
     */
    private function localeFromRoute(Request $request): ?string
    {
        $routeName = (string) $request->route()?->getName();

        foreach (LocaleEnum::cases() as $locale) {
            if (Str::startsWith($routeName, Str::slug($locale->value).'.')) {
                return $locale->value;
            }
        }

        return null;
    }

    private function localeFromSession(Request $request): string
    {
        $locale = $request->session()->get(SessionKeyEnum::LANGUAGE->value);

        return is_string($locale) ? $locale : LocaleEnum::DE->value;
    }
}
