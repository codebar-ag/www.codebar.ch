@props([
    'href' => null,
    'label' => null,
    'title' => null,
    'target' => '_self',
    'variant' => 'default',
    'size' => 'sm',
])

@php
    // Every chip on the site — meta badges, tag lists, news topic filters, partner
    // tiers, model links — is this component. Add a variant here, never a one-off
    // pill somewhere in a view.
    $variants = [
        'default' => 'bg-gray-400/10 text-muted ring-1 ring-gray-400/20 ring-inset',
        'outline' => 'text-muted ring-1 ring-border ring-inset',
        'solid' => 'bg-gray-900 text-white',
        'brand' => 'bg-brand text-white',
        // Live status — currently the opening-hours chip on the contact page.
        'success' => 'bg-emerald-500/10 text-emerald-700 ring-1 ring-emerald-600/20 ring-inset',
        // The partner tier chip — the one place a gradient earns its keep.
        'metal' => 'bg-linear-to-b from-gray-100 via-white to-gray-300 text-gray-700 ring-1 ring-gray-400/40 ring-inset',
    ];

    $hovers = [
        'default' => 'hover:bg-gray-400/20 hover:text-gray-800',
        'outline' => 'hover:text-brand hover:ring-brand',
        'solid' => 'hover:bg-gray-800',
        'brand' => 'hover:bg-brand-strong',
        'success' => '',
        'metal' => '',
    ];

    $sizes = [
        'xs' => 'px-2 py-0.5 text-xs',
        'sm' => 'px-2.5 py-1 text-sm',
        'md' => 'px-3.5 py-1.5 text-sm',
    ];

    $classes = trim(implode(' ', array_filter([
        'inline-flex items-center justify-center rounded-pill font-medium',
        $variants[$variant] ?? $variants['default'],
        $sizes[$size] ?? $sizes['sm'],
        // A chip that can be clicked has to be reachable with a thumb, so it grows
        // to the shared control height on a phone and stays compact from sm up.
        $href ? 'min-h-control sm:min-h-0 transition cursor-pointer focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand' : null,
        $href ? ($hovers[$variant] ?? '') : null,
    ])));
@endphp

@if($href)
    <a href="{{ $href }}"
       @if($target !== '_self') target="{{ $target }}" rel="noopener noreferrer" @endif
       @if(filled($title)) title="{{ $title }}" @endif
       {{ $attributes->merge(['class' => $classes]) }}>
        {{ $label }}
        {{ $slot }}
    </a>
@else
    <span @if(filled($title)) title="{{ $title }}" @endif
          {{ $attributes->merge(['class' => $classes]) }}>
        {{ $label }}
        {{ $slot }}
    </span>
@endif
