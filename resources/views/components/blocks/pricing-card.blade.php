@props([
    'name',
    'description' => null,
    'priceChf',
    'period' => null,
    'currency' => 'CHF',
    'featured' => false,
    'badge' => null,
    'classAttributes' => '',
])

@php
    $period ??= __('month');

    $borderClasses = $featured
        ? 'border-brand ring-2 ring-brand/40 shadow-xl shadow-brand/10'
        : 'border-zinc-200';

    $priceSizeClasses = $featured
        ? 'text-5xl md:text-6xl'
        : 'text-4xl md:text-5xl';

    $formattedPrice = number_format((float) $priceChf, 2, '.', "'");
@endphp

<article {{ $attributes->merge(['class' => "relative flex flex-col rounded-xl border bg-white p-8 md:p-10 {$borderClasses} {$classAttributes}"]) }}>
    @if(filled($badge))
        <span class="absolute right-6 top-6 inline-flex items-center rounded-full bg-brand px-3 py-1 text-xs font-medium uppercase tracking-[0.18em] text-white">
            {{ $badge }}
        </span>
    @endif

    <h3 class="text-xl font-semibold tracking-tight text-zinc-950">{{ $name }}</h3>

    <div class="mt-6 flex items-baseline gap-2">
        <span class="text-sm font-medium uppercase tracking-[0.18em] text-zinc-500">{{ $currency }}</span>
        <span class="{{ $priceSizeClasses }} font-semibold tracking-tight text-zinc-950">{{ $formattedPrice }}</span>
        @if(filled($period))
            <span class="text-base text-zinc-500">/ {{ $period }}</span>
        @endif
    </div>

    @if(filled($description))
        <p class="mt-4 text-base leading-relaxed text-zinc-600">{{ $description }}</p>
    @endif

    @if(trim($slot))
        <div class="mt-6">{{ $slot }}</div>
    @endif
</article>
