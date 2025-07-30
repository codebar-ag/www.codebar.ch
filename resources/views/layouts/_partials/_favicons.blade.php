@props(['manifest' => asset('manifest.json'), 'path' => asset('favicons'), 'color' => '#ffffff'])

@php
    $prefix = match($configuration?->key) {
        '_paperflakes' => 'paperflakes',
        '_codebar' => 'codebar',
        default => $configuration?->key
    };
@endphp

<link rel="icon" type="image/png" href="{{ asset("favicons/{$prefix}/favicon-96x96.png") }}" sizes="96x96"/>
<link rel="icon" type="image/svg+xml" href="{{ asset("favicons/{$prefix}/favicon.svg") }}"/>
<link rel="shortcut icon" href="{{ asset("favicons/{$prefix}/favicon.ico") }}"/>
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset("favicons/{$prefix}/apple-touch-icon.png") }}"/>
<link rel="manifest" href="{{ asset("favicons/{$prefix}/site.webmanifest") }}"/>

<meta name="msapplication-TileColor" content="{{ $color }}"/>
<meta name="msapplication-TileImage" content="{{ asset("favicons/{$prefix}/ms-icon-144x144.png") }}"/>
<meta name="theme-color" content="{{ $color }}"/>
