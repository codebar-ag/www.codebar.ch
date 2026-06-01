@props([
    'title' => null,
    'classAttributes' => '',
])

<div {{ $attributes->merge(['class' => $classAttributes]) }}>
    @if(filled($title))
        <h3 class="text-xs font-medium uppercase tracking-[0.2em] text-zinc-950">{{ $title }}</h3>
    @endif
    <div class="@if(filled($title)) mt-5 @endif space-y-3">
        {{ $slot }}
    </div>
</div>
