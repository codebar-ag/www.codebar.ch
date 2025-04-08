<?php

namespace App\Http\Middleware;

use App\Actions\LocaleAction;
use App\Enums\LocaleEnum;
use App\Enums\SessionKeyEnum;
use Closure;
use Illuminate\Http\Request;
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
        $locale = $request->session()->has(SessionKeyEnum::LANGUAGE->value)
            ? $request->session()->get(SessionKeyEnum::LANGUAGE->value)
            : LocaleEnum::DE->value;

        (new LocaleAction($locale))->setLocale();

        return $next($request);
    }
}
