@props(['href', 'label' => null, 'direction' => 'forward'])

<x-ui.link :href="$href" {{ $attributes->merge(['class' => 'group inline-flex tap-target items-center gap-1.5 text-base font-medium text-brand']) }}>
    @if($direction === 'back')
        <x-icon.arrow-right class="size-4 rotate-180 transition-transform group-hover:-translate-x-1"/>
    @endif

    {{ $slot->isEmpty() ? $label : $slot }}

    @if($direction !== 'back')
        <x-icon.arrow-right class="size-4 transition-transform group-hover:translate-x-1"/>
    @endif
</x-ui.link>
