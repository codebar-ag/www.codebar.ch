<?php

namespace App\Http\Middleware;

use App\Enums\LocaleEnum;
use App\Enums\SessionKeyEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLanguage
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->has(SessionKeyEnum::LANGUAGE->value)
            ? (string) $request->session()->get(SessionKeyEnum::LANGUAGE->value)
            : LocaleEnum::DE->value;

        app()->setLocale($locale);

        return $next($request);
    }
}
