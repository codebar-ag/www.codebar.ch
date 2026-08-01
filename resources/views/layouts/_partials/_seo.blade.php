<!-- Browser Configuration (manifest + theme-color come from _favicons.blade.php) -->
<meta name="application-name" content="{{ config('app.name') }}">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">

{{--<meta name="msapplication-config" content="{{ asset('browserconfig.xml') }}">--}}

@if(!empty($page))
    @php
        $seoImage = \App\Support\NewsImage::crawlable($page->image, config()->integer('seo.image_width'))
            ?? url(asset(config('seo.default_image')));

        // Detail pages ship a PageDTO per language (referencePages); each already carries
        // its own locale segment and its own translated slug, so they are the source of
        // truth. Everything else has no model parameters and can be derived by name.
        $alternatePages = collect([$page])->merge($page->referencePages ?? []);

        $hreflangAlternates = $alternatePages
            ->mapWithKeys(function ($alternate) {
                try {
                    return [str_replace('_', '-', $alternate->locale) => $alternate->url()];
                } catch (\Throwable) {
                    return [];
                }
            })
            ->when(
                $page->referencePages === null,
                fn ($alternates) => collect(\App\Enums\LocaleEnum::cases())
                    ->mapWithKeys(function ($locale) use ($page) {
                        $parameters = is_array($page->routeParameters)
                            ? \App\Support\LocalizedRouteParameters::for($page->routeParameters, $locale->value)
                            : $page->routeParameters;

                        try {
                            $url = route(\Illuminate\Support\Str::slug($locale->value).'.'.$page->routeKey, $parameters, true);
                        } catch (\Throwable) {
                            return [];
                        }

                        return [str_replace('_', '-', $locale->value) => $url];
                    })
            )
            ->sortKeys();

        $brand = config()->string('app.name');
        $documentTitle = $page->isArticle() || str_contains($page->title, $brand)
            ? $page->title
            : $page->title.' – '.$brand;
    @endphp
    <title>{{ $documentTitle }}</title>
    <meta name="robots" content="{{ $page->robots }}">
    <meta name="description" content="{{ $page->description }}">
    <link rel="canonical" href="{{ $page->url() }}">
    @foreach($hreflangAlternates as $hreflang => $url)
        <link rel="alternate" hreflang="{{ $hreflang }}" href="{{ $url }}">
    @endforeach
    @if($hreflangAlternates->isNotEmpty())
        <link rel="alternate" hreflang="x-default" href="{{ $hreflangAlternates->first() }}">
    @endif

    <meta property="og:locale" content="{{ $page->locale }}">
    <meta property="og:type" content="{{ $page->isArticle() ? 'article' : 'website' }}">
    @if($page->isArticle())
        <meta property="article:published_time" content="{{ $page->publishedAt->toIso8601String() }}">
        <meta property="article:modified_time" content="{{ $page->lastModificationDate->toIso8601String() }}">
        @if(filled($page->authorName))
            <meta property="article:author" content="{{ $page->authorName }}">
        @endif
    @endif
    <meta property="og:title" content="{{ $page->title }}">
    <meta property="og:description" content="{{ $page->description }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:url" content="{{ $page->url() }}">
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


