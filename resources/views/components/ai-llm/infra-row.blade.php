@props(['label', 'text'])

<div class="grid grid-cols-1 sm:grid-cols-[12rem_1fr] gap-2 sm:gap-6 border-t border-gray-100 py-4 px-2">
    <div class="font-semibold text-gray-800">{{ $label }}</div>
    <div class="text-gray-600">
        {{ $text }}
        {{ $slot }}
    </div>
</div>
