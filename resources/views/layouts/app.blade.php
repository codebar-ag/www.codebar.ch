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

    <!-- Preload critical resources: the latin subsets actually used above the fold —
         400 for body copy, 600 for every heading and UI label, 700 for the h1. 300 and
         500 carry secondary text only and are left to font-display: swap.

         Vite::asset, not asset(): the faces are built assets with a content hash in the
         name, and the preload has to name the exact file the stylesheet will ask for or
         the browser fetches the font twice. -->
    <link rel="preload" href="{{ Vite::asset('resources/fonts/poppins/poppins-400-normal-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ Vite::asset('resources/fonts/poppins/poppins-600-normal-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ Vite::asset('resources/fonts/poppins/poppins-700-normal-latin.woff2') }}" as="font" type="font/woff2" crossorigin>

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

<div class="flex min-h-screen flex-col {{ $outer }}">

    <header class="{{ $inner }}">
        @include('layouts._partials._navigation')
    </header>

    <main id="main" class="my-section flex-1">
        {{-- The site's body scale, whatever the layout. $wide switches the *frame*, and it
             used to take the type size with it — so an article's breadcrumbs, byline, tags
             and series nav sat at 16px while every other page ran at 18px. The reading
             column re-states its own size in .news-prose; the chrome around it should not
             have to. --}}
        <div class="text-lg leading-relaxed">
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
