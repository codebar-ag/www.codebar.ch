@props(['title', 'intro' => null, 'page' => null, 'breadcrumbs' => []])

@php
    // Explicit intro wins; otherwise fall back to the page's SEO description
    // so every page header carries context without duplicating copy.
    $context = $intro ?? $page?->description;
@endphp

<x-breadcrumbs :items="$breadcrumbs"/>

<x-h1 :title="$title"/>

@if(filled($context))
    {{-- One lead treatment for the whole site. Detail pages used to set this
         semibold and index pages light, so the same slot read as two different
         things depending on where you had come from. --}}
    <p class="max-w-3xl text-lead font-light text-gray-800">{{ $context }}</p>
@endif
