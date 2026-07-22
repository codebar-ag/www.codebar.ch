@props(['label', 'text'])

<x-ui.row class="sm:grid-cols-[12rem_1fr]">
    <div class="font-semibold text-gray-800">{{ $label }}</div>
    <div class="text-muted">
        {{ $text }}
        {{ $slot }}
    </div>
</x-ui.row>
