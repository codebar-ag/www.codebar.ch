@props(['href','label','target' => '_self','classAttributes' => "", 'title' => null])

<x-ui.link
    :href="$href"
    :label="$label"
    :target="$target"
    :title="$title"
    class-attributes="{{ $classAttributes }}"
/>