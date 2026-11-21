    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <!-- Resource hints for performance -->
    @if (! empty($preconnectCloudinary))
        <link rel="preconnect" href="https://res.cloudinary.com">
    @endif
    <link rel="dns-prefetch" href="https://res.cloudinary.com">

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
