@props(['url', 'label', 'teaser'])

<a href="{{ $url }}"
   {{ $attributes->merge(['class' => 'group flex cursor-pointer flex-col gap-1 rounded-panel border border-border bg-linear-to-r from-fuchsia-600/10 via-brand/10 to-blue-600/10 p-4 transition-colors hover:border-gray-300 hover:from-fuchsia-600/20 hover:via-brand/20 hover:to-blue-600/20 focus-ring']) }}>
    <span class="flex items-center justify-between gap-2">
        <span class="text-base font-bold text-gray-800 group-hover:text-brand">{{ $label }}</span>
        <x-icon.arrow-right class="size-4 shrink-0 text-brand transition-transform group-hover:translate-x-1"/>
    </span>
    <span class="truncate text-sm text-muted">{{ $teaser }}</span>
</a>
