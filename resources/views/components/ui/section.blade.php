@props([
    'classAttributes' => '',
    'spacing' => 'default',
    'surface' => false,
])

@php
    $spacingClasses = match ($spacing) {
        'none' => '',
        'tight' => 'py-4 md:py-6',
        'loose' => 'py-10 md:py-14',
        default => 'py-6 md:py-8',
    };
@endphp

<section {{ $attributes->merge(['class' => "{$spacingClasses} {$classAttributes}"]) }}>
    @if($surface)
        <div class="surface-card editorial-divider p-6 md:p-8">
            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endif
</section>
