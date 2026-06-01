@props(['items' => []])

<dl class="flex flex-wrap gap-x-10 gap-y-4 border-t border-zinc-200 pt-6 text-sm">
    @foreach($items as $item)
        <div>
            <dt class="text-xs font-medium uppercase tracking-[0.18em] text-zinc-500">{{ $item['label'] ?? '' }}</dt>
            <dd class="mt-1 font-medium text-zinc-950">{{ $item['value'] ?? '' }}</dd>
        </div>
    @endforeach
</dl>
