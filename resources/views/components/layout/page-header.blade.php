@props(['title', 'intro' => null, 'page' => null])

@php
    // Explicit intro wins; otherwise fall back to the page's SEO description
    // so every page header carries context without duplicating copy.
    $context = $intro ?? $page?->description;
@endphp

<x-h1 :title="$title"/>

@if(filled($context))
    <p class="max-w-2xl text-gray-800">{{ $context }}</p>
@endif
