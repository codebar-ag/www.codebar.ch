@props(['href', 'label' => null, 'direction' => 'forward'])

{{-- The «read on» link: a label with a directional arrow that slides on hover.
     The arrow is decorative — the label alone has to say where the link goes. --}}
<x-ui.link :href="$href" {{ $attributes->merge(['class' => 'group inline-flex min-h-control items-center gap-1.5 text-base font-medium text-brand sm:min-h-0']) }}>
    @if($direction === 'back')
        <x-icon.arrow-right class="size-4 rotate-180 transition-transform group-hover:-translate-x-1"/>
    @endif

    {{ $slot->isEmpty() ? $label : $slot }}

    @if($direction !== 'back')
        <x-icon.arrow-right class="size-4 transition-transform group-hover:translate-x-1"/>
    @endif
</x-ui.link>
