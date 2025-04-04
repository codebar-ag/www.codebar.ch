@props(['manifest' => asset('manifest.json'), 'path' => asset('favicons'), 'color' => '#ffffff'])

<link rel="icon" type="image/png" href="{{ asset('favicons/favicon-96x96.png') }}" sizes="96x96"/>
<link rel="icon" type="image/svg+xml" href="{{ asset('favicons/favicon.svg') }}"/>
<link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}"/>
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-touch-icon.png') }}"/>
<link rel="manifest" href="{{ asset('favicons/site.webmanifest') }}"/>

<meta name="msapplication-TileColor" content="{{ $color }}"/>
<meta name="msapplication-TileImage" content="{{ $path.'/ms-icon-144x144.png' }}"/>
<meta name="theme-color" content="{{ $color }}"/>