@props(['items' => []])

<x-ui.card class-attributes="ui-region bg-[var(--surface-muted)]">
    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
        @foreach($items as $item)
            <div class="rounded-lg border border-[var(--border-subtle)] bg-white/80 px-3 py-2">
                <p class="text-xs uppercase tracking-[0.14em] text-[var(--text-soft)]">{{ $item['label'] ?? '' }}</p>
                <p class="mt-1 text-sm font-semibold text-[var(--text-strong)]">{{ $item['value'] ?? '' }}</p>
            </div>
        @endforeach
    </div>
</x-ui.card>
