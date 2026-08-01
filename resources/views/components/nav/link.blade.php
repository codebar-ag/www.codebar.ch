@props(['route', 'label', 'variant' => 'desktop'])

@php
    use Illuminate\Support\Str;

    $current = Str::after(request()->route()?->getName() ?? '', '.');
    $isActive = filled($current) && Str::before($current, '.') === Str::before($route, '.');

    $variants = [
        'desktop' => [
            'base' => 'rounded-pill px-1 text-xl transition md:text-2xl focus-ring',
            'idle' => 'hover:text-brand',
            'active' => 'font-semibold text-brand',
        ],
        'mobile' => [
            'base' => 'flex min-h-control items-center rounded-pill text-2xl transition focus-ring',
            'idle' => 'text-gray-800 hover:text-brand',
            'active' => 'font-semibold text-brand',
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
