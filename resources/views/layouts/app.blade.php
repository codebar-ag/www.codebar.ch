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

    <!-- Preload critical resources: the latin subsets actually used above the fold -->
    <link rel="preload" href="{{ asset('fonts/poppins/poppins-400-normal-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('fonts/poppins/poppins-700-normal-latin.woff2') }}" as="font" type="font/woff2" crossorigin>

    @include('layouts._partials._seo')
    @include('layouts._partials._schema')
    @include('layouts._partials._favicons')

    @vite(['resources/js/app.js'])

</head>
<body class="font-sans antialiased min-h-screen bg-white text-gray-800">

<a href="#main"
   class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:rounded-pill focus:bg-white focus:px-4 focus:py-2 focus:ring-2 focus:ring-brand">
    {{ __('Skip to content') }}
</a>

@php
    // The one page frame, written once. Exactly one of $outer and $inner carries it:
    // a normal page is framed once around everything, while an editorial page lays
    // out its own columns, so the frame moves inward onto the header, the next-page
    // card and the footer.
    $frame = 'mx-auto w-full max-w-frame px-4 sm:px-6 lg:px-8';
    $outer = $wide ? '' : $frame;
    $inner = $wide ? $frame : '';
@endphp

<div class="{{ $outer }}">

    <header class="{{ $inner }}">
        @include('layouts._partials._navigation')
    </header>

    <main id="main" class="my-section">
        <div class="{{ $wide ? '' : 'text-lg leading-relaxed' }}">
            {{ $slot }}

            <div class="{{ $inner }}">
                <x-next-page/>
            </div>
        </div>
    </main>

    <div class="{{ $inner }}">
        @include('layouts._partials._footer')
    </div>

</div>

@include('layouts._partials._fathom')

</body>
</html>
