@props(['path' => asset('favicons/codebar'), 'color' => '#ffffff'])

<link rel="icon" type="image/png" href="{{ $path }}/favicon-96x96.png" sizes="96x96"/>
<link rel="icon" type="image/svg+xml" href="{{ $path }}/favicon.svg"/>
<link rel="shortcut icon" href="{{ $path }}/favicon.ico"/>
<link rel="apple-touch-icon" sizes="180x180" href="{{ $path }}/apple-touch-icon.png"/>
<link rel="manifest" href="{{ $path }}/site.webmanifest"/>

<meta name="msapplication-TileColor" content="{{ $color }}"/>
<meta name="msapplication-TileImage" content="{{ $path }}/ms-icon-144x144.png"/>
<meta name="theme-color" content="{{ $color }}"/>
