@props(['classAttributes' => ''])

<x-ui.list :class-attributes="$classAttributes">
    {{ $slot }}
</x-ui.list>
