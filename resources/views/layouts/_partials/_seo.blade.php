<!-- Browser Configuration (manifest + theme-color come from _favicons.blade.php) -->
<meta name="application-name" content="{{ config('app.name') }}">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">

{{--<meta name="msapplication-config" content="{{ asset('browserconfig.xml') }}">--}}

@if(!empty($page))
    @php
        $seoImage = $page->image ?: url(asset(config('seo.default_image')));

        $hreflangAlternates = collect(\App\Enums\LocaleEnum::cases())
            ->mapWithKeys(function ($locale) use ($page) {
                $parameters = $page->routeParameters;

                // Detail routes carry the locale in the path as well as in the
                // route-name prefix (/news/{locale}/{slug}). Without swapping it
                // the English alternate points at the German article.
                if (is_array($parameters) && array_key_exists('locale', $parameters)) {
                    $parameters['locale'] = $locale->value;
                }

                try {
                    $url = route(\Illuminate\Support\Str::slug($locale->value).'.'.$page->routeKey, $parameters, true);
                } catch (\Throwable) {
                    return [];
                }

                return [str_replace('_', '-', $locale->value) => $url];
            });
    @endphp
    <title>{{ $page->title }}</title>
    <meta name="robots" content="{{ $page->robots }}">
    <meta name="description" content="{{ $page->description }}">
    <link rel="canonical" href="{{ request()->url() }}">
    @foreach($hreflangAlternates as $hreflang => $url)
        <link rel="alternate" hreflang="{{ $hreflang }}" href="{{ $url }}">
    @endforeach
    @if($hreflangAlternates->isNotEmpty())
        <link rel="alternate" hreflang="x-default" href="{{ $hreflangAlternates->first() }}">
    @endif

    <meta property="og:locale" content="{{ $page->locale }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $page->title }}">
    <meta property="og:description" content="{{ $page->description }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:image" content="{{ $seoImage }}">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:alt" content="{{ config('app.name') }}">
    <meta property="og:image:width" content="{{ config('seo.image_width') }}">
    <meta property="og:image:height" content="{{ config('seo.image_height') }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $page->title }}">
    <meta name="twitter:description" content="{{ $page->description }}">
    <meta name="twitter:image" content="{{ $seoImage }}">
@else
    {{-- No page metadata: error pages and anything else rendered without a
         PageDTO. These must never enter the index — without an explicit robots
         tag an error page competes with real content in search results. --}}
    <title>{{ config('app.name') }}</title>
    <meta name="robots" content="noindex,follow">
@endif


