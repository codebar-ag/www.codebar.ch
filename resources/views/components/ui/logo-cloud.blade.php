@props([
    'eyebrow' => null,
    'classAttributes' => '',
])

<div {{ $attributes->merge(['class' => $classAttributes]) }}>
    @if(filled($eyebrow))
        <p class="text-xs font-medium uppercase tracking-[0.22em] text-zinc-500">{{ $eyebrow }}</p>
    @endif
    <div class="@if(filled($eyebrow)) mt-8 @endif grid grid-cols-2 items-center gap-x-12 gap-y-10 text-zinc-400 sm:grid-cols-3 lg:grid-cols-5">
        {{ $slot }}
    </div>
</div>
