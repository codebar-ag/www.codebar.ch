@props([
    'title',
    'description',
    'robots' => 'index,follow',
    'image' => null,
])

@php
    // Built from the request, never from config('app.url'): AppServiceProvider
    // pins that to the main site's host for the whole app, and the Zunscan
    // middleware only corrects Laravel's URL generator — request()->url() is
    // the honest answer for this domain either way. Query strings are dropped
    // so a ?utm_* variant does not become its own canonical.
    $canonical = request()->url();
    $locale = str_replace('_', '-', app()->getLocale());
@endphp

<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title }} | zunscan.ch</title>
    <meta name="robots" content="{{ $robots }}">
    <meta name="description" content="{{ $description }}">
    <link rel="canonical" href="{{ $canonical }}">

    <meta property="og:locale" content="{{ str_replace('-', '_', $locale) }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:site_name" content="zunscan.ch">
    <meta property="og:url" content="{{ $canonical }}">
    @if($image)
        <meta property="og:image" content="{{ $image }}">
    @endif

    @foreach(\App\Enums\LocaleEnum::cases() as $alternate)
        <link rel="alternate" hreflang="{{ str_replace('_', '-', $alternate->value) }}"
              href="{{ zunscan_locale_switch_url($alternate->value) }}">
    @endforeach
    {{-- Same target as the main site's x-default: the German start page. --}}
    <link rel="alternate" hreflang="x-default"
          href="{{ zunscan_locale_switch_url(\App\Enums\LocaleEnum::DE->value) }}">

    {{-- Zunscan had no favicon at all, so every page logged a 404 for
         /favicon.ico. The logo is already an SVG, so it doubles as the icon. --}}
    <link rel="icon" href="{{ asset('images/zunscan/zunscan_logo_pos.svg') }}" type="image/svg+xml">

    <link rel="preconnect" href="https://res.cloudinary.com" crossorigin>

    <x-zunscan.patials.schema :title="$title" :description="$description" :canonical="$canonical"/>

    @vite(['resources/js/zunscan.js'])
</head>

<body class="font-sans antialiased text-zunscan-dark-gray">
{{ $slot }}

<x-zunscan.patials.footer/>
</body>
</html>
