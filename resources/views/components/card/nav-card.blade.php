@props(['url', 'label', 'teaser', 'route' => null])

@php
    // One accent hue per main page so the explore tiles carry a bit of colour.
    $accent = match ($route) {
        'services.index' => 'border-l-sky-400',
        'about-us.index' => 'border-l-amber-400',
        'ai.index' => 'border-l-violet-400',
        'network.index' => 'border-l-emerald-400',
        'contact.index' => 'border-l-rose-400',
        default => 'border-l-brand',
    };
@endphp

<a href="{{ $url }}"
   class="group flex flex-col gap-1 rounded-panel border border-gray-200 border-l-4 {{ $accent }} p-4 transition hover:bg-gray-50/50">
    <span class="flex items-center justify-between gap-2">
        <span class="text-base font-bold text-gray-800 group-hover:text-brand">{{ $label }}</span>
        <x-icon.arrow-right class="size-4 shrink-0 text-brand transition-transform group-hover:translate-x-1"/>
    </span>
    <span class="truncate text-sm text-muted">{{ $teaser }}</span>
</a>
