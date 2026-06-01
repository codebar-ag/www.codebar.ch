@props(['classAttributes' => ''])

<div {{ $attributes->merge(['class' => "mx-auto max-w-3xl {$classAttributes}"]) }}>
    @isset($aside)
        <div class="grid gap-12 lg:grid-cols-[minmax(0,1fr)_16rem]">
            <div class="min-w-0 prose prose-zinc max-w-none">
                {{ $slot }}
            </div>
            <aside class="lg:pt-2 text-sm text-zinc-500">
                {{ $aside }}
            </aside>
        </div>
    @else
        <div class="prose prose-zinc max-w-none">
            {{ $slot }}
        </div>
    @endisset
</div>
