@props(['classAttributes' => ""])

<x-ui.section class-attributes="text-gray-800 {{ $classAttributes }}">
    {{ $slot }}
</x-ui.section>
