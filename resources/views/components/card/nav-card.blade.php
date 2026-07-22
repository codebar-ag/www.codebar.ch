@props(['url', 'label', 'teaser'])

<a href="{{ $url }}"
   class="group flex flex-col gap-1 rounded-panel border border-gray-200 p-4 transition hover:bg-gray-50/50">
    <span class="flex items-center justify-between gap-2">
        <span class="text-base font-bold text-gray-800 group-hover:text-brand">{{ $label }}</span>
        <x-icon.arrow-right class="size-4 shrink-0 text-brand transition-transform group-hover:translate-x-1"/>
    </span>
    <span class="truncate text-sm text-muted">{{ $teaser }}</span>
</a>
