<!DOCTYPE html>
<html lang="de" class="scroll-smooth">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>{{ $variantTitle ?? 'Flows layout demo' }} — Flows demo</title>
    <meta name="robots" content="noindex, nofollow"/>
    <link rel="preload" href="{{ asset('fonts/poppins/poppins-regular.woff2') }}" as="font" type="font/woff2" crossorigin>
    @vite(['resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-white text-gray-800">

@unless($hideDemoBar ?? false)
    <div class="sticky top-0 z-50 flex items-center justify-between gap-4 bg-zinc-950 text-white text-sm px-4 py-2">
        <a href="{{ route('demo.flows.index') }}" class="font-semibold hover:text-white/70 transition">← Alle 10 Layouts</a>
        <span class="text-white/50 truncate">{{ $variantTitle ?? '' }}</span>
    </div>
@endunless

@yield('content')

</body>
</html>
