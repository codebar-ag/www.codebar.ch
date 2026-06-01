@props(['classAttributes' => ''])

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full border border-zinc-200 bg-zinc-50 px-2.5 py-0.5 text-xs font-medium text-zinc-600 {$classAttributes}"]) }}>
    {{ $slot }}
</span>
