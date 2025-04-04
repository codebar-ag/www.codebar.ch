<!DOCTYPE html>
<html
        lang="{{ str_replace('_', '-', app()->getLocale()) }}"
        class="scroll-smooth"
>
<head>
    <meta charset="utf-8"/>
    <meta
            name="viewport"
            content="width=device-width, initial-scale=1"
    />
    <meta
            name="csrf-token"
            content="{{ csrf_token() }}"
    />

    <title>{{ config('app.name') }}</title>

    @include('layouts._partials._favicons')


    <!-- Fonts -->
    <link
            rel="preconnect"
            href="https://fonts.googleapis.com"
    />
    <link
            rel="preconnect"
            href="https://fonts.gstatic.com"
            crossorigin
    />
    <link
            href="https://fonts.googleapis.com/css2?family=Poppins&display=swap"
            rel="stylesheet"
    />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="font-sans antialiased">

<main class="min-h-screen bg-white text-gray-800">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        @include('layouts._partials._navigation')

        <section class="my-8 bg-gray-100/50">
            <div class="text-lg leading-relaxed max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                {{ $slot }}
            </div>
        </section>

        @include('layouts._partials._footer')

    </div>
</main>

@include('layouts._partials._fathom')

</body>
</html>
