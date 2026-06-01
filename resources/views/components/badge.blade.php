@props(['label', 'title' => '', 'classAttributes' => ''])

<x-ui.badge :label="$label" :title="$title" :class-attributes="$classAttributes">
    {{ $slot }}
</x-ui.badge>
