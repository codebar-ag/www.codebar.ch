    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <!-- Resource hints for performance -->
    @if (! empty($preconnectCloudinary))
        <link rel="preconnect" href="https://res.cloudinary.com">
    @endif
    <link rel="dns-prefetch" href="https://res.cloudinary.com">

    <!-- Preload critical resources: the latin subset of the one variable font, which
         carries every weight used above the fold.

         Vite::asset, not asset(): the faces are built assets with a content hash in the
         name, and the preload has to name the exact file the stylesheet will ask for or
         the browser fetches the font twice. -->
    <link rel="preload" href="{{ Vite::asset('resources/fonts/alan-sans/alan-sans-variable-latin.woff2') }}" as="font" type="font/woff2" crossorigin>

    @include('layouts._partials._seo')
    @include('layouts._partials._schema')
    @include('layouts._partials._favicons')

    @vite(['resources/js/app.js'])
