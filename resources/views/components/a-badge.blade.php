@props(['href','label','target' => '_self','classAttributes' => "", 'title' => null])

<x-ui.button
    :href="$href"
    :label="$label"
    variant="outline"
    :target="$target"
    class-attributes="rounded-full {{ $classAttributes }}"
    title="{{ $title ?? $label }}"
>
    {{ $slot }}
</x-ui.button>
