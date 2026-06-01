<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="scroll-smooth [view-transition-name:root]"
>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <link rel="preconnect" href="https://res.cloudinary.com">
    <link rel="dns-prefetch" href="https://res.cloudinary.com">
    <link rel="preconnect" href="https://cdn.usefathom.com">
    <link rel="dns-prefetch" href="https://cdn.usefathom.com">

    @include('layouts._partials._seo')
    @include('layouts._partials._favicons')

    {{-- Language-fade in-phase: runs synchronously before paint to avoid FOUC. --}}
    <script>
        (function () {
            try {
                var raw = sessionStorage.getItem('langFadeIn');
                if (!raw) return;
                sessionStorage.removeItem('langFadeIn');
                var data = JSON.parse(raw);
                if (Date.now() - (data.t || 0) > 3000) return;
                var html = document.documentElement;
                html.classList.add('is-fading-in');
                requestAnimationFrame(function () {
                    requestAnimationFrame(function () {
                        html.classList.remove('is-fading-in');
                    });
                });
            } catch (e) {}
        })();
    </script>

    @vite(['resources/js/app.js'])
</head>
<body class="bg-white font-sans text-zinc-800 antialiased">
    <div class="flex min-h-screen flex-col">
        @include('layouts._partials._navigation')

        <main class="flex-1">
            {{ $slot }}
        </main>

        @include('layouts._partials._footer')
    </div>

    @include('layouts._partials._fathom')
</body>
</html>
