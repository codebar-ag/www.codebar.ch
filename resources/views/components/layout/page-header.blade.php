@props(['title', 'intro' => null, 'page' => null, 'breadcrumbs' => null])

@php
    $context = $intro ?? $page?->description;
    $trail = $breadcrumbs === null ? [['label' => $title]] : $breadcrumbs;
@endphp

<div class="relative left-1/2 w-screen -translate-x-1/2 border-y border-border bg-surface">
    <div class="mx-auto w-full max-w-frame px-4 py-8 sm:px-6 sm:py-10 lg:px-8 [&>:last-child]:mb-0">
        <x-breadcrumbs :items="$trail"/>

        @isset($eyebrow)
            {{ $eyebrow }}
        @endisset

        <x-h1 :title="$title" :class="isset($eyebrow) ? 'mt-4' : ''"/>

        @if(filled($context))
            <x-layout.lead>{{ $context }}</x-layout.lead>
        @endif

        @isset($meta)
            <div class="mt-6">{{ $meta }}</div>
        @endisset
    </div>
</div>
