<!-- Manifest and Browser Configuration -->
<link rel="manifest" href="{{ asset('manifest.json') }}">
<meta name="theme-color" content="#ffffff">
<meta name="application-name" content="{{ config('app.name') }}">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">

{{--<meta name="msapplication-config" content="{{ asset('browserconfig.xml') }}">--}}

@if(!empty($page))
    <title>{{ $page->title }}</title>
    <meta name="robots" content="{{ $page->robots }}">
    <meta name="description" content="{{ $page->description }}">
    <meta name="language" Content="{{ $page->locale }}">
    <meta name="url" content="{{ request()->url() }}">

    <meta property="og:locale" content="{{ $page->locale }}">
    <meta property="og:type" content="">
    <meta property="og:title" content="{{ $page->title }}">
    <meta property="og:description" content="{{ $page->description }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:image" content="{{ $page->image }}">

    <meta name="twitter:site" content="'twitter_site'">
    <meta name="twitter:site:id" content="twitter_site_id">
    <meta name="twitter:card" content="twitter_card">
    <meta name="twitter:title" content="{{ $page->title }}">
    <meta name="twitter:description" content="{{ $page->description }}">
    <meta name="twitter:image" content="{{ $page->image }}">
@endif



