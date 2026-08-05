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

    <meta name="application-name" content="zunscan.ch">
    <meta name="apple-mobile-web-app-title" content="zunscan.ch">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#16395a">

    <meta property="og:locale" content="{{ str_replace('-', '_', $locale) }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:site_name" content="zunscan.ch">
    <meta property="og:url" content="{{ $canonical }}">
    @if($image)
        {{-- Dimensions are declared so a crawler can lay the card out before the
             image has downloaded; without them the preview often falls back to a
             small thumbnail. The Cloudinary transform pins the same 1200×630. --}}
        <meta property="og:image" content="{{ $image }}">
        <meta property="og:image:type" content="image/jpeg">
        <meta property="og:image:alt" content="zunscan.ch">
        <meta property="og:image:width" content="{{ config('seo.image_width') }}">
        <meta property="og:image:height" content="{{ config('seo.image_height') }}">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $title }}">
        <meta name="twitter:description" content="{{ $description }}">
        <meta name="twitter:image" content="{{ $image }}">
    @endif

    @foreach(\App\Enums\LocaleEnum::cases() as $alternate)
        <link rel="alternate" hreflang="{{ str_replace('_', '-', $alternate->value) }}"
              href="{{ zunscan_locale_switch_url($alternate->value) }}">
    @endforeach
    {{-- Same target as the main site's x-default: the German start page. --}}
    <link rel="alternate" hreflang="x-default"
          href="{{ zunscan_locale_switch_url(\App\Enums\LocaleEnum::DE->value) }}">

    {{-- Zunscan's own icon set, mirroring the main site's _favicons partial.
         The wordmark SVG is 246×87, so using it directly letterboxed into a
         sliver at 16px; these are the paperclip glyph on a square plate. --}}
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicons/zunscan/favicon-96x96.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicons/zunscan/favicon.svg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/zunscan/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('favicons/zunscan/site.webmanifest') }}">

    <link rel="preconnect" href="https://res.cloudinary.com" crossorigin>

    <x-zunscan.patials.schema :title="$title" :description="$description" :canonical="$canonical" :image="$image"/>

    @vite(['resources/js/zunscan.js'])
</head>

<body class="font-sans antialiased text-zunscan-dark-gray">
{{ $slot }}

<x-zunscan.patials.footer/>
</body>
</html>
