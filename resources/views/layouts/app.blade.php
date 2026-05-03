<!DOCTYPE html>
<html
        lang="{{ str_replace('_', '-', app()->getLocale()) }}"
        class="scroll-smooth [view-transition-name:root]"
>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
  
    <!-- Resource hints for performance -->
    <link rel="preconnect" href="https://res.cloudinary.com">
    <link rel="dns-prefetch" href="https://res.cloudinary.com">
    <link rel="preconnect" href="https://cdn.usefathom.com">
    <link rel="dns-prefetch" href="https://cdn.usefathom.com">

    <!-- Preload critical resources -->
    <link rel="preload" href="{{ asset('fonts/poppins/poppins-regular.woff2') }}" as="font" type="font/woff2" crossorigin>

    @include('layouts._partials._seo')
    @include('layouts._partials._favicons')

    @vite(['resources/js/app.js'])

</head>
<body class="font-sans antialiased" style="--brand-color: {{ $configuration?->company_primary_color ?? '#500472' }};">

<main class="app-shell text-gray-800">
    <div class="app-container px-4 sm:px-6 lg:px-8">

        @include('layouts._partials._navigation')

        <section class="section-spacing editorial-divider">
            <div class="editorial-shell text-base leading-relaxed md:text-lg">
                {{ $slot }}
            </div>
        </section>

        @include('layouts._partials._footer')

    </div>
</main>

@include('layouts._partials._fathom')

</body>
</html>
