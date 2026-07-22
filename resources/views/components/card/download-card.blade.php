@props(['label', 'image', 'links' => [], 'inverted' => false])

<x-ui.panel variant="plain" class="flex flex-col gap-4 p-6">
    <div @class([
        'flex min-h-[80px] items-center justify-center rounded-panel p-4',
        'bg-gray-950' => $inverted,
        'bg-white' => ! $inverted,
    ])>
        <img src="{{ $image }}" alt="{{ $label }}" class="max-h-16 w-auto"/>
    </div>
    <div class="flex flex-col gap-2">
        <span class="font-semibold text-gray-800">{{ $label }}</span>
        <div class="flex gap-4">
            @foreach($links as $link)
                <x-ui.link :href="$link['href']" :label="$link['label']" :download="$link['download']" class="text-base"/>
            @endforeach
        </div>
    </div>
</x-ui.panel>
