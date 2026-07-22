@props(['title', 'intro' => null, 'page' => null])

@php
    // Explicit intro wins; otherwise fall back to the page's SEO description
    // so every page header carries context without duplicating copy.
    $context = $intro ?? $page?->description;
@endphp

<x-h1 :title="$title"/>

@if(filled($context))
    {{-- Lead text: one step above the body copy (text-lg) so the header keeps its hierarchy. --}}
    <p class="max-w-3xl text-xl md:text-2xl font-light leading-normal text-gray-800">{{ $context }}</p>
@endif
