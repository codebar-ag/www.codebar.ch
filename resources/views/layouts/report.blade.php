<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts._partials._head')
</head>
<body class="font-sans antialiased min-h-screen bg-surface text-gray-800">

<a href="#main"
   class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:rounded-pill focus:bg-white focus:px-4 focus:py-2 focus:ring-2 focus:ring-brand">
    {{ __('Skip to content') }}
</a>

<div class="mx-auto flex min-h-screen w-full max-w-4xl flex-col px-4 sm:px-6">

    <header class="flex items-center justify-between gap-4 border-b border-border pt-10 pb-6">
        <a href="{{ localized_route('start.index') }}" title="{{ __('Home') }}" class="inline-block rounded-pill focus-ring">
            <img src="{{ asset('images/logos/codebar-logo-colored.svg') }}" alt="codebar" width="565" height="120"
                 class="block h-auto max-h-9 w-auto">
        </a>
        @isset($actions)
            <div class="flex shrink-0 gap-2">{{ $actions }}</div>
        @endisset
    </header>

    <main id="main" class="flex-1 pt-12 pb-16">
        <div class="text-lg leading-relaxed">
            {{ $slot }}
        </div>
    </main>

</div>

@include('layouts._partials._codebar_analytics')

</body>
</html>
