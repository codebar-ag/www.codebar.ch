@props(['route', 'label', 'variant' => 'desktop'])

@php
    use Illuminate\Support\Str;

    // Route names are locale-prefixed (de-ch.services.show), so drop the prefix and
    // compare sections: a detail page keeps its section lit — services.show still
    // marks «Services» as the page you are on.
    $current = Str::after(request()->route()?->getName() ?? '', '.');
    $isActive = filled($current) && Str::before($current, '.') === Str::before($route, '.');

    $variants = [
        'desktop' => [
            'base' => 'rounded-pill px-1 text-xl transition md:text-2xl focus:outline-none focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand',
            'idle' => 'hover:text-brand',
            'active' => 'font-semibold text-brand',
        ],
        'mobile' => [
            'base' => 'flex min-h-control items-center justify-center px-4 text-xl transition focus:outline-none focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-brand',
            'idle' => 'text-gray-800 hover:text-brand',
            'active' => 'bg-brand/10 font-semibold text-brand',
        ],
    ];

    $style = $variants[$variant] ?? $variants['desktop'];
@endphp

<a href="{{ localized_route($route) }}"
   title="{{ $label }}"
   @if($isActive) aria-current="page" @endif
   {{ $attributes->merge(['class' => $style['base'].' '.($isActive ? $style['active'] : $style['idle'])]) }}>
    {{ $label }}
</a>
