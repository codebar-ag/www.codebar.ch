@props(['title', 'open' => false, 'status' => null, 'complete' => false])

<details {{ $attributes->merge(['class' => 'group border border-border rounded-panel bg-surface']) }}{!! $open ? ' open' : '' !!}>
    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-6 py-4 focus-ring [&::-webkit-details-marker]:hidden">
        <span class="flex min-w-0 items-center gap-3">
            <span class="text-subheading font-semibold text-balance text-gray-900">{{ $title }}</span>
            @if(filled($status))
                <span class="shrink-0 rounded-pill px-2 py-0.5 text-xs font-semibold {{ $complete ? 'bg-green-600/10 text-green-800' : 'bg-gray-400/10 text-gray-700' }}">{{ $status }}</span>
            @endif
        </span>
        <x-icon.chevron-down class="size-5 shrink-0 text-muted transition group-open:rotate-180"/>
    </summary>
    <div class="space-y-6 border-t border-border px-6 pb-6 pt-5">
        {{ $slot }}
    </div>
</details>
