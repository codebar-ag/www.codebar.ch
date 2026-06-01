@props([
    'classAttributes' => '',
    'spacing' => 'default',
    'tone' => 'default',
    'divider' => true,
])

@php
    $spacingClasses = match ($spacing) {
        'none' => '',
        'tight' => 'py-12 md:py-16',
        'loose' => 'py-24 md:py-32 lg:py-40',
        default => 'py-16 md:py-20 lg:py-28',
    };

    $toneClasses = match ($tone) {
        'muted' => 'bg-zinc-50',
        'dark' => 'bg-zinc-950 text-zinc-200',
        default => '',
    };

    $dividerClass = $divider && $tone === 'default' ? 'border-t border-zinc-200 first:border-t-0' : '';
@endphp

<section {{ $attributes->merge(['class' => trim("{$dividerClass} {$toneClasses} {$spacingClasses} {$classAttributes}")]) }}>
    <div class="mx-auto max-w-6xl px-6 lg:px-8">
        {{ $slot }}
    </div>
</section>
