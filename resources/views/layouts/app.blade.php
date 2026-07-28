<!DOCTYPE html>
<html
        lang="{{ str_replace('_', '-', app()->getLocale()) }}"
        class="scroll-smooth"
>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <!-- Resource hints for performance -->
    @if (! empty($preconnectCloudinary))
        <link rel="preconnect" href="https://res.cloudinary.com">
    @endif
    <link rel="dns-prefetch" href="https://res.cloudinary.com">
    <link rel="preconnect" href="https://cdn.usefathom.com">
    <link rel="dns-prefetch" href="https://cdn.usefathom.com">

    <!-- Preload critical resources -->
    <link rel="preload" href="{{ asset('fonts/poppins/poppins-regular.woff2') }}" as="font" type="font/woff2" crossorigin>

    @include('layouts._partials._seo')
    @include('layouts._partials._schema')
    @include('layouts._partials._favicons')

    @vite(['resources/js/app.js'])

</head>
<body class="font-sans antialiased min-h-screen bg-white text-gray-800">

<a href="#main"
   class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:bg-white focus:px-4 focus:py-2 focus:rounded focus:ring-2 focus:ring-brand">
    {{ __('Skip to content') }}
</a>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    <header>
        @include('layouts._partials._navigation')
    </header>

    <main id="main" class="my-8">
        <div class="text-lg leading-relaxed">
            {{ $slot }}

            <x-next-page/>
        </div>
    </main>

    @include('layouts._partials._footer')

</div>

@include('layouts._partials._fathom')

</body>
</html>
