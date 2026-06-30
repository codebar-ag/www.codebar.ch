@props(['manifest' => asset('manifest.json'), 'path' => asset('favicons'), 'color' => '#ffffff'])

@php
    $prefix = match ($configuration?->key) {
        '_paperflakes' => 'paperflakes',
        '_codebar' => 'codebar',
        default => filled($configuration?->key)
            ? ltrim((string) $configuration->key, '_')
            : 'codebar',
    };

    $faviconPath = rtrim($path, '/')."/{$prefix}";
@endphp

<link rel="icon" type="image/png" href="{{ "{$faviconPath}/favicon-96x96.png" }}" sizes="96x96"/>
<link rel="icon" type="image/svg+xml" href="{{ "{$faviconPath}/favicon.svg" }}"/>
<link rel="shortcut icon" href="{{ "{$faviconPath}/favicon.ico" }}"/>
<link rel="apple-touch-icon" sizes="180x180" href="{{ "{$faviconPath}/apple-touch-icon.png" }}"/>
<link rel="manifest" href="{{ "{$faviconPath}/site.webmanifest" }}"/>

<meta name="msapplication-TileColor" content="{{ $color }}"/>
<meta name="msapplication-TileImage" content="{{ "{$faviconPath}/web-app-manifest-192x192.png" }}"/>
<meta name="theme-color" content="{{ $color }}"/>
