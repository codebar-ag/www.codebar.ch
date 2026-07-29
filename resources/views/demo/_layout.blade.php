{{-- The standalone shell for the layout prototypes. Deliberately not the app
     layout: each variant is a full-bleed design exploration that brings its own
     header, palette and <main>, so the real site chrome would only get in the way.
     It is noindex and unreachable from the navigation. --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>{{ $variantTitle ?? 'Flows layout demo' }} — Flows demo</title>
    <meta name="robots" content="noindex, nofollow"/>
    <link rel="preload" href="{{ asset('fonts/poppins/poppins-400-normal-latin.woff2') }}" as="font" type="font/woff2" crossorigin>
    @vite(['resources/js/app.js'])
</head>
<body class="bg-white font-sans text-gray-800 antialiased">

@unless($hideDemoBar ?? false)
    <x-demo.bar :href="route('demo.flows.index')" label="Alle 10 Layouts" :title="$variantTitle ?? ''"/>
@endunless

@yield('content')

</body>
</html>
